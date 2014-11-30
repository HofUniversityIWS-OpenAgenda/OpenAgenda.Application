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
	 * @Flow\Inject
	 * @var \TYPO3\Party\Domain\Repository\PartyRepository
	 */
	protected $partyRepository;

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

		$person = $this->personFactory->createAnonymousPersonWithElectronicAddress(
			$newAccount->getUsername()
		);
		$account->setParty($person);

		$this->accountRepository->add($account);
		$this->persistenceManager->persistAll();

		$this->messagingService->prepareForAccount($account, 'Account/Create');
	}

	public function confirmAction() {

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

}