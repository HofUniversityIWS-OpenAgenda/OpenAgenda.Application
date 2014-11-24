<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\MeetingController;
use OpenAgenda\Application\CalendarController;
use OpenAgenda\Application\TaskController;

class DashboardController extends ActionController {

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