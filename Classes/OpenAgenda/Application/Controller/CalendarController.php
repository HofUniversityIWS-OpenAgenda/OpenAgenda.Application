<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class CalendarController
 *
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
class CalendarController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository
	 */
	protected $meetingRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @return void
	 */
	public function dashboardAction() {
	}

	/**
	 * @return void
	 */
	public function listAction() {
	}

}