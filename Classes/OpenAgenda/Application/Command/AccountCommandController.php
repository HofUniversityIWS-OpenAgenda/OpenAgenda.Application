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
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

	/**
	 * ### Administration Action to add roles ###
	 *
	 * Roles: Administrator, Participant, MeetingManager
	 *
	 * @param string $identifier email of account
	 * @param string $role name of role
	 */
	public function setRoleCommand($identifier, $role) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, 'DefaultProvider');
		$roleValidString = 'OpenAgenda.Application:' . $role;
		$role = $this->policyService->getRole($roleValidString);
		$account->addRole($role);
		$this->accountRepository->update($account);
		$this->response->appendContent('Set role ' . $role . ' for account ' . $identifier . PHP_EOL);
	}

	/**
	 * ### Administrator Action to remove role ###
	 *
	 * Roles: Administrator, Participant, MeetingManager
	 *
	 * @param string $identifier email of account
	 * @param string $role name of role
	 */
	public function removeRoleCommand($identifier, $role) {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, 'DefaultProvider');
		$roleValidString = 'OpenAgenda.Application:' . $role;
		$role = $this->policyService->getRole($roleValidString);
		$account->removeRole($role);
		$this->accountRepository->update($account);
		$this->response->appendContent('Removed role ' . $role . ' for account ' . $identifier . PHP_EOL);
	}
}