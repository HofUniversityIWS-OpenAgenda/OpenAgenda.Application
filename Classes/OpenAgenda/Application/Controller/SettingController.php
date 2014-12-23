<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Person;
use OpenAgenda\Application\Domain\Model\Preference;
use OpenAgenda\Application\Framework\Model\Password;

/**
 * Class SettingController
 *
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
class SettingController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @return void
	 */
	public function getProfileAction() {
		$this->view->assign('value', $this->arrayService->prepare($this->securityContext->getParty()));
	}

	/**
	 * @param Person $person
	 * @param Password $password
	 * @Flow\Validate(argumentName="person", type="OpenAgenda.Application:ModelScope", options={"scopeName"="updateProfile"})
	 * @Flow\Validate(argumentName="password", type="OpenAgenda.Application:ModelScope", options={"scopeName"="updateProfile"})
	 */
	public function updateProfileAction(Person $person, Password $password = NULL) {
		$this->personRepository->update($person);

		if ($password !== NULL && strlen($password->getPassword()) > 0) {
			$account = $this->securityContext->getAccount();
			$account->setCredentialsSource($this->hashService->hashPassword($password->getPassword()));
			$this->accountRepository->update($account);
		}

		$this->view->assign('value', $this->arrayService->prepare($person));
	}

	/**
	 * @return void
	 */
	public function getSettingAction() {
		/** @var Person $person */
		$person = $this->securityContext->getParty();
		$this->view->assign('value', $this->arrayService->flatten($person->getPreference()));
	}

	/**
	 * @param Preference $preference
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function updateSettingAction(Preference $preference) {
		/** @var Person $person */
		$person = $this->securityContext->getParty();
		$person->setPreference($preference);
		$this->personRepository->update($person);
	}

}