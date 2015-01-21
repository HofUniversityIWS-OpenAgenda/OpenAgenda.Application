<?php
namespace OpenAgenda\Application\Tests\Functional\Controller\Fixture;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group small
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class MeetingController extends \OpenAgenda\Application\Controller\MeetingController {

	/**
	 * This action shows all allowed meetings.
	 *
	 * @return void
	 */
	public function listAction()
	{
		$this->view->assign('value', $this->arrayService->flatten($this->meetingRepository->findAll(), 'list'));
	}
}