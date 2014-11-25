<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\Account;

class StandardController extends AbstractController {

	/**
	 * @return void
	 */
	public function indexAction() {
		#$this->redirect('login', 'Authentication');
	}

}