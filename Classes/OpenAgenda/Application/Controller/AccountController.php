<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\Account;

class AccountController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('accounts', $this->accountRepository->findAll());
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Account $account
	 * @return void
	 */
	public function showAction(Account $account) {
		$this->view->assign('account', $account);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Account $newAccount
	 * @return void
	 */
	public function createAction(Account $newAccount) {
		$this->accountRepository->add($newAccount);
		$this->addFlashMessage('Created a new account.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Account $account
	 * @return void
	 */
	public function editAction(Account $account) {
		$this->view->assign('account', $account);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Account $account
	 * @return void
	 */
	public function updateAction(Account $account) {
		$this->accountRepository->update($account);
		$this->addFlashMessage('Updated the account.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Account $account
	 * @return void
	 */
	public function deleteAction(Account $account) {
		$this->accountRepository->remove($account);
		$this->addFlashMessage('Deleted a account.');
		$this->redirect('index');
	}

}