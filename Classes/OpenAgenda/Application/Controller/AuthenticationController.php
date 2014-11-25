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

	public function newAction() {

	}

	/**
	 * @param string $username
	 * @param string $password
	 * @param string $passwordRepeat
	 */
	public function createAction($username, $password, $passwordRepeat) {

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