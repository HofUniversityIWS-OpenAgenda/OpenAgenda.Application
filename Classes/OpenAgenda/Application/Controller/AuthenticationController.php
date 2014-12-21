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
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Factory\PersonFactory
	 */
	protected $personFactory;

	/**
	 * @var array
	 * @Flow\Inject(setting="Authentication")
	 */
	protected $authenticationSettings;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\Communication\MessagingService
	 */
	protected $messagingService;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\Security\ArgumentService
	 */
	protected $argumentService;

	/**
	 * Shows input form to create a new account.
	 */
	public function newAction() {
		$newAccount = new \OpenAgenda\Application\Framework\Model\Account();
		$this->view->assign('newAccount', $newAccount);
	}

	/**
	 * Creates a new account.
	 *
	 * @param \OpenAgenda\Application\Framework\Model\Account $newAccount
	 * @Flow\Validate(argumentName="newAccount", type="OpenAgenda.Application:ModelScope", options={"scopeName"="create"})
	 */
	public function createAction(\OpenAgenda\Application\Framework\Model\Account $newAccount = NULL) {
		if ($newAccount === NULL) {
			$this->redirect('new');
		}

		$account = $this->accountFactory->createAccountWithPassword(
			$newAccount->getUsername(),
			$newAccount->getPassword(),
			array($this->getDefaultRoleIdentifier())
		);

		// Disable account until it has been confirmed:
		$account->setExpirationDate(\DateTime::createFromFormat('U', 0));

		$person = $this->personFactory->createPerson(
			$newAccount->getUsername(),
			$newAccount->getFirstName(),
			$newAccount->getLastName()
		);
		$account->setParty($person);

		$this->accountRepository->add($account);
		$this->persistenceManager->persistAll();

		$this->messagingService->prepareForAccount($account, 'Account/Create');
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $account
	 */
	public function confirmAction(\TYPO3\Flow\Security\Account $account = NULL) {
		if ($account === NULL || $account->getExpirationDate() === NULL) {
			return;
		}

		try {
			$this->argumentService->validate($this->request->getArguments());
			$account->setExpirationDate(NULL);
			$this->accountRepository->update($account);
		} catch (\TYPO3\Flow\Security\Exception $exception) {
			return;
		}

		$this->view->assign('success', TRUE);
	}

	public function forgotAction() {

	}

	public function recoverAction() {

	}

	/**
	 * Logs out and ends the current account session.
	 *
	 * @return void
	 */
	public function logoutAction() {
		$this->authenticationManager->logout();

		if (!empty($this->authenticationSettings['postLogoutUri'])) {
			$this->redirectToUri($this->authenticationSettings['postLogoutUri']);
		}

		$this->redirectToUri('/');
	}

	/**
	 * @param \TYPO3\Flow\Mvc\ActionRequest $originalRequest
	 * @return string|void
	 * @throws \TYPO3\Flow\Mvc\Exception\StopActionException
	 */
	protected function onAuthenticationSuccess(\TYPO3\Flow\Mvc\ActionRequest $originalRequest = NULL) {
		$this->updateAccountEnvironment();

		if ($originalRequest !== NULL) {
			$this->redirectToRequest($originalRequest);
		}

		if (!empty($this->authenticationSettings['postLoginUri'])) {
			$this->redirectToUri($this->authenticationSettings['postLoginUri']);
		}

		$this->redirectToUri('/');
	}

	/**
	 * @return string
	 */
	protected function getDefaultRoleIdentifier() {
		$roleIdentifier = self::ROLE_DefaultRole;
		if (!empty($this->authenticationSettings['defaultRole'])) {
			$roleIdentifier = $this->authenticationSettings['defaultRole'];
		}
		return $roleIdentifier;
	}

	/**
	 * Updates account environment (account, person, ...)
	 *
	 * @return void
	 */
	protected function updateAccountEnvironment() {
		$person = $this->securityContext->getParty();
		$this->personFactory->updatePerson($person);
	}

}