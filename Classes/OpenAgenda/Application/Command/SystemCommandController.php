<?php
namespace OpenAgenda\Application\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * Class SystemCommandController
 *
 * This command controller takes care of handling system tasks.
 * Invoke this controller by using a CLI call (e.g. cronjob), like:
 *
 * <code>
 * ./flow system:<action-name>
 * </code>
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Command
 * @author Oliver Hader <oliver@typo3.org>
 */
class SystemCommandController extends CommandController {

	/**
	* @Flow\Inject
	* @var \OpenAgenda\Application\Service\Communication\MessagingService
	*/
	protected $messagingService;

	/**
	 * Sends queued messages.
	 *
	 * Invoke by calling
	 *
	 * <code>
	 * ./flow system:sendmessages
	 * </code>
	 *
	 * @return void
	 * @author Oliver Hader <oliver@typo3.org>
	 * @see \OpenAgenda\Application\Service\Communication\MessagingService::prepareForPerson
	 */
	public function sendMessagesCommand() {
		$this->messagingService->deliver();
	}

}