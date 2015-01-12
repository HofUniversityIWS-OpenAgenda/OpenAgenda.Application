<?php
namespace OpenAgenda\Application\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * Class AccountCommandController
 *
 * This command controller is for admin actions to handle user accounts.
 *
 * <code>
 * ./flow account:<action-name>
 * </code>
 *
 * @package OpenAgenda\Application\Command
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
	 * This administration action changes the role of an user account.
	 *
	 * Possible name of roles: Administrator, Participant
	 *
	 * <code>
	 * ./flow account:setrole --<parameters>
	 * </code>
	 *
	 * @param string $identifier The email address of an account
	 * @param string $role The name of the role
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
	 * This administration action removes a role of an user account.
	 *
	 * Possible name of roles: Administrator, Participant
	 *
	 * <code>
	 * ./flow account:removerole --<parameters>
	 * </code>
	 *
	 * @param string $identifier The email address of an account
	 * @param string $role The name of the role
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