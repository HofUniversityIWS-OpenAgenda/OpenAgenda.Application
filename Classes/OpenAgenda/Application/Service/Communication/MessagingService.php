<?php
namespace OpenAgenda\Application\Service\Communication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \OpenAgenda\Application\Domain\Model\Person;
use TYPO3\Flow\Security\Account;
use OpenAgenda\Application\Domain\Model\Message;

/**
 * Class MessagingService
 *
 * The service renders, queues and delivers messages.
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Communication
 * @author Oliver Hader <oliver@typo3.org>
 */
class MessagingService {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	 */
	protected $messageRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @var array
	 * @Flow\Inject(setting="Messaging")
	 */
	protected $messagingSettings;

	/**
	 * Prepares a message to be delivered to an Account entity.
	 *
	 * @param Account $account The recipient entity
	 * @param string $messageName The name of the message template to be used
	 * @param array $variables Additional variables to used during rendering
	 * @param array|\TYPO3\Flow\Resource\Resource[] $attachments Additional resources used as attachments
	 * @return void
	 */
	public function prepareForAccount(Account $account, $messageName, array $variables = array(), $attachments = array()) {
		$variables['account'] = $account;
		$this->prepareForPerson($account->getParty(), $messageName, $variables, $attachments);
	}

	/**
	 * Prepares a message to be delivered to a Person entity.
	 *
	 * @param Person $person The recipient entity
	 * @param string $messageName The name of the message template to be used
	 * @param array $variables Additional variables to used during rendering
	 * @param array|\TYPO3\Flow\Resource\Resource[] $attachments Additional resources used as attachments
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @return void
	 */
	public function prepareForPerson(Person $person, $messageName, array $variables = array(), $attachments = array()) {
		$variables['person'] = $person;

		$view = $this->createView($messageName, $variables);
		$htmlBody = $view->render($messageName, $variables);
		// @todo For readable plain text view, strip_tags is too much in terms of removing information
		$textBody = strip_tags($htmlBody);
		$subject = $view->getSubject();

		$message = Message::create();
		$message->setRecipient($person);
		$message->setSubject($subject);
		$message->setRichTextBody($htmlBody);
		$message->setPlainTextBody($textBody);

		if (!empty($attachments)) {
			$attachmentCollection = new \Doctrine\Common\Collections\ArrayCollection($attachments);
			$message->setAttachments($attachmentCollection);
		}

		$this->messageRepository->add($message);
		$this->persistenceManager->persistAll();

		if (!empty($this->messagingSettings['deliver'])
			&& $this->messagingSettings['deliver'] === 'immediately') {
			$this->deliverMessage($message);
		}
	}

	/**
	 * Delivers messages from the queue that have
	 * not been delivered yet or failed recently.
	 *
	 * @return void
	 */
	public function deliver() {
		$status = array(
			Message::STATUS_Created,
			Message::STATUS_Failure,
		);

		$messages = $this->messageRepository->findByStatus($status);

		foreach ($messages as $message) {
			$this->deliverMessage($message);
		}
	}

	/**
	 * Delivers a particular message.
	 *
	 * @param Message $message The message to be delivered
	 * @return bool Whether the delivery attempt has been successful
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	protected function deliverMessage(Message $message) {
		if ($message->isActive()) {
			return FALSE;
		}

		$result = FALSE;

		try {
			$message->setStatus(Message::STATUS_Active);
			$this->messageRepository->update($message);
			$this->persistenceManager->persistAll();

			$mailMessage = $this->createMailMessage($message);
			$mailMessage->send();

			if (count($mailMessage->getFailedRecipients()) === 0) {
				$message->setStatus(Message::STATUS_Delivered);
				$result = TRUE;
			} else {
				$message->setStatus(Message::STATUS_Failure);
			}

			$this->messageRepository->update($message);
			$this->persistenceManager->persistAll();
		} catch (\Exception $exception) {
			$message->setStatus(Message::STATUS_Failure);
			$this->messageRepository->update($message);
			$this->persistenceManager->persistAll();
		}

		return $result;
	}

	/**
	 * Creates a new mail message from a given message entity.
	 *
	 * **Scopes**
	 *
	 * + \OpenAgenda\Application\Domain\Model\Message is used
	 *   to aggregate message information concerning the OpenAgenda domain
	 * + \TYPO3\SwiftMailer\Message is used to actually execute
	 *   the mail delivery process using SwiftMailer as transportation service
	 *
	 * @param Message $message The message entity to be transformed to a mail message
	 * @return \TYPO3\SwiftMailer\Message Mail message ready to be delivered using SwiftMailer
	 */
	protected function createMailMessage(Message $message) {
		$sender = array(
			$this->messagingSettings['sender']['mail']
				=> $this->messagingSettings['sender']['name']
		);
		$recipient = array(
			$message->getRecipient()->getPrimaryElectronicAddress()->getIdentifier()
				=> $message->getRecipient()->getName()->getFullName()
		);

		$mailMessage = new \TYPO3\SwiftMailer\Message();

		if ($message->getAttachments()->count()) {
			foreach ($message->getAttachments() as $attachment) {
				$mailMessage->attach(
					\Swift_Attachment::newInstance(
						file_get_contents($attachment->getUri()),
						$attachment->getFilename() . '.' . $attachment->getFileExtension(),
						$attachment->getMediaType()
					)
				);
			}
		}

		return $mailMessage
			->setSender($sender)
			->setFrom($sender)
			->setTo($recipient)
			->setSubject($message->getSubject())
			->setBody($message->getPlainTextBody(), 'text/plain')
			->setBody($message->getRichTextBody(), 'text/html');
	}

	/**
	 * Creates the Fluid template rendering view.
	 *
	 * Templates reside at ./Resources/Private/Messages/<scope>/<name>.html
	 *
	 * **Explanation**
	 *
	 * + *scope* is a convention for the accordant domain entity
	 *   concern (e.g. Meeting, Task, Account, ...)
	 * + *name* is the actual template name, the html file extension
	 *   is appended automatically
	 * + the argument *$messageName* combined both - *scope* and *name*
	 *
	 *
	 * @param string $messageName The message name (e.g. "Meeting/Invite")
	 * @param array $variables Additional variables to be used during rendering
	 * @return \OpenAgenda\Application\View\MessageView
	 * @throws \TYPO3\Flow\Package\Exception\UnknownPackageException
	 */
	protected function createView($messageName, array $variables) {
		$basePath = rtrim($this->packageManager->getPackage('OpenAgenda.Application')->getResourcesPath(), '/') . '/Private/';

		$view = new \OpenAgenda\Application\View\MessageView();
		// @todo Template paths must be configurable
		$view->setTemplatePathAndFilename($basePath . 'Messages/' . $messageName . '.html');
		$view->setPartialRootPath($basePath . 'Partials/');
		$view->setLayoutRootPath($basePath . 'Layouts/');
		$view->assignMultiple($variables);

		return $view;
	}

}