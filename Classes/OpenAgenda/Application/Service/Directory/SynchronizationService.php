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
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\ElectronicAddressRepository
	 */
	protected $electronicAddressRepository;

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 */
	public function create(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		$this->setRoles($account, $ldapSearchResult);
		$this->setPerson($account, $ldapSearchResult);
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 */
	public function update(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		$this->setRoles($account, $ldapSearchResult);
		$this->setPerson($account, $ldapSearchResult);
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 * @throws \TYPO3\Flow\Security\Exception\NoSuchRoleException
	 */
	protected function setRoles(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		if (count($account->getRoles()) === 0) {
			$role = $this->policyService->getRole($this->getDefaultRoleIdentifier());
			$account->addRole($role);
		}
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 */
	protected function setPerson(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		if (empty($this->authenticationSettings['directory']['mailIdentifier'])) {
			return;
		}

		$mailIdentifier = $this->authenticationSettings['directory']['mailIdentifier'];

		if (empty($ldapSearchResult[$mailIdentifier])) {
			return;
		}

		$mailAddresses = $ldapSearchResult[$mailIdentifier];
		if (isset($mailAddresses['count'])) {
			unset($mailAddresses['count']);
		}

		$electronicAddresses = $this->electronicAddressRepository->findByMailAddresses($mailAddresses);
		$persons = $this->personRepository->findByElectronicAddresses($electronicAddresses->toArray());

		if ($persons->count() > 0) {
			$account->setParty($persons->getFirst());
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