<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;


class AbstractController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	* @Flow\Inject
	* @var \OpenAgenda\Application\Security\PermissionService
	*/
	protected $permissionService;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Communication\MessagingService
	 */
	protected $messagingService;

	/**
	 * @param \TYPO3\Flow\Mvc\View\ViewInterface $view The view to be initialized
	 * @return void
	 * @api
	 */
	protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view) {
	}

	/**
	 * @return void
	 */
	protected  function initializeAction() {

	}

	/**
	 * @return \TYPO3\Flow\Security\Account
	 */
	protected function getAccount() {
		return $this->securityContext->getAccount();
	}

}