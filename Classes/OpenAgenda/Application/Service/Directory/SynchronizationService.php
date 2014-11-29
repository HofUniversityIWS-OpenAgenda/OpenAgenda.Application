<?php
namespace OpenAgenda\Application\Service\Directory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class LdapBindService
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Directory
 * @author Oliver Hader <oliver@typo3.org>
 */
class SynchronizationService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

	/**
	 * @var array
	 * @Flow\Inject(setting="Authentication")
	 */
	protected $authenticationSettings;

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 */
	public function create(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		$this->setRoles($account);
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 */
	public function update(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		$this->setRoles($account);
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @throws \TYPO3\Flow\Security\Exception\NoSuchRoleException
	 */
	protected function setRoles(\TYPO3\Flow\Security\Account $account) {
		if (count($account->getRoles()) === 0) {
			$role = $this->policyService->getRole($this->getDefaultRoleIdentifier());
			$account->addRole($role);
		}
	}

	/**
	 * @return string
	 */
	protected function getDefaultRoleIdentifier() {
		$roleIdentifier = \OpenAgenda\Application\Controller\AuthenticationController::ROLE_DefaultRole;
		if (!empty($this->authenticationSettings['defaultRole'])) {
			$roleIdentifier = $this->authenticationSettings['defaultRole'];
		}
		return $roleIdentifier;
	}

}