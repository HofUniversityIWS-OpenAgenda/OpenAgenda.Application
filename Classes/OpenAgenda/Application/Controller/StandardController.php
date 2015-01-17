<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\Account;

/**
 * Class StandardController
 *
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
class StandardController extends AbstractController {

	const LOGIN_Action = 'login';
	const LOGIN_Controller = 'Authentication';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Utility\Now
	 */
	protected $now;

	/**
	 * Default and basic action that is called if nothing else applied.
	 * This method passes to view if anybody is logged in or redirects
	 * to the authentication controller to ask for user credentials.
	 *
	 * @return void
	 */
	public function indexAction() {
		if ($this->getAccount() === NULL) {
			$this->redirect(self::LOGIN_Action, self::LOGIN_Controller);
		}
	}

	/**
	 * Pings the server and sends the current timestamp.
	 *
	 * @return void
	 */
	public function pingAction() {
		$value = array(
			'time' => $this->now->format('U')
		);
		$this->view->assign('value', $value);
	}

}