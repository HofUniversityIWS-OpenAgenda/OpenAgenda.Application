<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class AuthenticationController
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
class AuthenticationController extends \TYPO3\Flow\Security\Authentication\Controller\AbstractAuthenticationController {

	const ROLE_DefaultRole = 'Anonymous';

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
	 * @var array
	 * @Flow\Inject(setting="Authentication")
	 */
	protected $authenticationSettings;

	/**
	 * Shows input form to create a new account.
	 */
	public function newAction() {
		$newAccount = new \OpenAgenda\Application\Structure\Model\Account();
		$this->view->assign('newAccount', $newAccount);
	}

	/**
	 * Creates a new account.
	 *
	 * @param \OpenAgenda\Application\Structure\Model\Account $newAccount
	 * @Flow\Validate(argumentName="newAccount", type="OpenAgenda.Application:ModelScope", options={"scopeName"="create"})
	 */
	public function createAction(\OpenAgenda\Application\Structure\Model\Account $newAccount = NULL) {
		if ($newAccount === NULL) {
			$this->redirect('new');
		}

		$role = self::ROLE_DefaultRole;
		if (!empty($this->authenticationSettings['defaultRole'])) {
			$role = $this->authenticationSettings['defaultRole'];
		}

		$account = $this->accountFactory->createAccountWithPassword(
			$newAccount->getUsername(),
			$newAccount->getPassword(),
			array($role)
		);
		$this->accountRepository->add($account);
	}

	public function confirmAction() {

	}

	public function forgotAction() {

	}

	public function recoverAction() {

	}

	protected function onAuthenticationSuccess(\TYPO3\Flow\Mvc\ActionRequest $originalRequest = NULL) {

	}

}