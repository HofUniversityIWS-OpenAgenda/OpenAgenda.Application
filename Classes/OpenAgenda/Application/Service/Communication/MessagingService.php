<?php
namespace OpenAgenda\Application\Service\Communication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Party\Domain\Model\Person;
use TYPO3\Flow\Security\Account;
use OpenAgenda\Application\Domain\Model\Message;

class MessagingService {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	 */
	protected $messageRepository;
	/**
	* @var
	*/
	protected $documentRenderingService;

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
	 * @param Account $account
	 * @param string $messageName
	 * @param array $variables
	 */
	public function prepareForAccount(Account $account, $messageName, array $variables = array()) {
		$variables['account'] = $account;
		$this->prepareForPerson($account->getParty(), $messageName, $variables);
	}

	/**
	 * @param Person $person
	 * @param string $messageName
	 * @param array $variables
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function prepareForPerson(Person $person, $messageName, array $variables = array()) {
		$variables['person'] = $person;

		$view = $this->createView($messageName, $variables);
		$subject = $view->getSubject();
		$htmlBody = $view->render($messageName, $variables);
		// @todo For readable plain text view, strip_tags is too much in terms of removing information
		$textBody = strip_tags($htmlBody);

		$message = Message::create();
		$message->setRecipient($person);
		$message->setSubject($subject);
		$message->setRichTextBody($htmlBody);
		$message->setPlainTextBody($textBody);

		$this->messageRepository->add($message);
	}

	public function deliver() {
		$status = array(
			Message::STATUS_Created,
			Message::STATUS_Failure,
		);

		$messages = $this->messageRepository->findByStatus($status);

		foreach ($messages as $message) {
			if ($message->isActive()) {
				continue;
			}

			try {
				$message->setStatus(Message::STATUS_Active);
				$this->messageRepository->update($message);
				$this->persistenceManager->persistAll();

				$this->createMailMessage($message)->send();

				$message->setStatus(Message::STATUS_Delivered);
				$this->messageRepository->update($message);
				$this->persistenceManager->persistAll();
			} catch (\Exception $exception) {
				$message->setStatus(Message::STATUS_Failure);
				$this->messageRepository->update($message);
				$this->persistenceManager->persistAll();
			}
		}
	}

	/**
	 * @param Message $message
	 * @return \TYPO3\SwiftMailer\Message
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
		return $mailMessage
			->setSender($sender)
			->setFrom($sender)
			->setTo($recipient)
			->setSubject($message->getSubject())
			->setBody($message->getPlainTextBody(), 'text/plain')
			->setBody($message->getRichTextBody(), 'text/html');
	}

	/**
	 * @param string $messageName
	 * @param array $variables
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