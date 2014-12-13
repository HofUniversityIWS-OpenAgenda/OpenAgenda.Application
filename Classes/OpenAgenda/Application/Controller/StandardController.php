<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\Account;

/**
 * Class StandardController
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
class StandardController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Utility\Now
	 */
	protected $now;

	/**
	 * @return void
	 */
	public function indexAction() {
		if ($this->getAccount() === NULL) {
			$this->redirect('login', 'Authentication');
		}
	}

	public function pingAction() {
		$value = array(
			'time' => $this->now->format('U')
		);
		$this->view->assign('value', $value);
	}

}