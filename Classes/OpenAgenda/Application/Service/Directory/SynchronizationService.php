<?php
namespace OpenAgenda\Application\Service\Directory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class SynchronizationService
 *
 * Synchronization of LDAP content with local Account and Person entities.
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
	 * Handles the Account creation (Account is new to the system).
	 * This method is invoked by the global LdapProvider using the
	 * Signal-Slot-Dispatcher.
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 * @return void
	 * @see \TYPO3\LDAP\Security\Authentication\Provider\LDAPProvider::emitCreateAccount
	 * @see \OpenAgenda\Application\Package::boot
	 */
	public function create(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		$this->setRoles($account, $ldapSearchResult);
		$this->setPerson($account, $ldapSearchResult);
	}

	/**
	 * Handles the Account updating (Account is already known to the system).
	 * This method is invoked by the global LdapProvider using the
	 * Signal-Slot-Dispatcher.
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 * @return void
	 * @see \TYPO3\LDAP\Security\Authentication\Provider\LDAPProvider::emitUpdateAccount
	 * @see \OpenAgenda\Application\Package::boot
	 */
	public function update(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		$this->setRoles($account, $ldapSearchResult);
		$this->setPerson($account, $ldapSearchResult);
	}

	/**
	 * Sets roles for a given Account entity.
	 * Currently only the default role is applied.
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult Retrieved LDAP content
	 * @return void
	 * @throws \TYPO3\Flow\Security\Exception\NoSuchRoleException
	 */
	protected function setRoles(\TYPO3\Flow\Security\Account $account, array $ldapSearchResult) {
		if (count($account->getRoles()) === 0) {
			$role = $this->policyService->getRole($this->getDefaultRoleIdentifier());
			$account->addRole($role);
		}
	}

	/**
	 * Sets additional information for a Person entity of an Account.
	 * The mail address is used to synchronize multiple Account entities
	 * with exactly one Person entity. The mailIdentifier contains a name
	 * that refers to an accordant property in the contents retrieved from
	 * the LDAP service.
	 *
	 * **Settings**
	 *
	 * *in Configuration/Settings.yaml or any other context specific
	 * configuration file of the global TYPO3 Flow instance*
	 *
	 * <code>
	 * OpenAgenda:
	 *   Application:
	 *     Authentication:
	 *       directory:
	 *         mailIdentifier: 'mail'
	 * </code>
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param array $ldapSearchResult
	 * @return void
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
	 * Gets the default role identifier used for Account entities.
	 *
	 * **Settings**
	 *
	 * *in Configuration/Settings.yaml or any other context specific
	 * configuration file of the global TYPO3 Flow instance*
	 *
	 * <code>
	 * OpenAgenda:
	 *   Application:
	 *     Authentication:
	 *       defaultRole: 'OpenAgenda.Application:Participant'
	 * </code>
	 *
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