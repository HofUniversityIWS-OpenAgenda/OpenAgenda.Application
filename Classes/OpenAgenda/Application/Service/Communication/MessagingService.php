<?php
namespace OpenAgenda\Application\Service\Communication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Account;

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

	public function prepareForAccount(Account $account, array $variables) {
		$recipient = $account->getParty();
		$variables['account'] = $account;
		$variables['recipient'] = $recipient;

		$message = \OpenAgenda\Application\Domain\Model\Message::create();
	}

}