<?php
namespace OpenAgenda\Application\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class SystemCommandController extends CommandController {

	/**
	* @Flow\Inject
	* @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	*/
	protected $messageRepository;

	/**
	 * Sends messages.
	 * @return void
	 */
	public function sendMessagesCommand() {

	}

}