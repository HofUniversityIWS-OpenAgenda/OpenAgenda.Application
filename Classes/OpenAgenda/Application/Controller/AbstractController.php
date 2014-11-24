<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;


class DashboardController extends ActionController {

/**
* @Flow\Inject
* @var \OpenAgenda\Application\Security\PermissionService
*/
	protected $permissionService;

	/**
	 * @return void
	 */
	public function indexAction() {
		
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function showAction(Meeting $meeting) {
		$this->view->assign('meeting', $meeting);
	}
}