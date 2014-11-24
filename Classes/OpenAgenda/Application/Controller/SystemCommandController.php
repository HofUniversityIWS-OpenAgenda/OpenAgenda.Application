<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;


class SystemCommandController extends CommandController {

	/**
	* @Flow\Inject
	* @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	*/
	protected $messageRepository;

	/**
	 * @return void
	 */
	protected function sendMessages() {
	}

}