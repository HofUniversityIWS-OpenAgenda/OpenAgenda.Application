<?php
namespace OpenAgenda\Application\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * CommandController for admin actions with accounts
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class AccountCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 * @Flow\Inject
	 */
	protected $accountFactory;

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

	/**
	 * ### title ###
	 *
	 * Roles: Administrator, Listener, Participant, MeetingChair, MeetingManager, Chairman
	 *
	 * @param string $email The of account
	 * @param string $role The name of role
	 */
	public function setRoleCommand($email, $role) {
		$roleValidString = 'OpenAgenda.Application:' . $role;

		// @todo set Role here

		$this->response->appendContent('Set role ' . $role . ' for account ' . $email . PHP_EOL);
	}
}