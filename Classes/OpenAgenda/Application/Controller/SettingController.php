<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Person;
use OpenAgenda\Application\Framework\Model\Password;

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

		if ($password !== NULL) {
			$account = $this->securityContext->getAccount();
			$account->setCredentialsSource($this->hashService->hashPassword($password->getPassword()));
			$this->accountRepository->update($account);
		}

		$this->view->assign('value', $this->arrayService->prepare($person));
	}

	public function getSettingAction() {

	}

	public function updateSettingAction() {

	}

}