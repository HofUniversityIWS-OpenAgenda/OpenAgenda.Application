<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;

class MeetingController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository
	 */
	protected $meetingRepository;

	/**
	 * @return void
	 */
	public function dashboardAction() {
	}

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('value', $this->meetingRepository->findAllowed()->toFlatArray('list'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function showAction(Meeting $meeting) {
		$this->view->assign('value', $meeting->toFlatArray('show'));
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $newMeeting
	 * @return void
	 */
	public function createAction(Meeting $newMeeting) {
		$this->meetingRepository->add($newMeeting);
		$this->addFlashMessage('Created a new meeting.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function editAction(Meeting $meeting) {
		$this->view->assign('meeting', $meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function updateAction(Meeting $meeting) {
		$this->meetingRepository->update($meeting);
		$this->addFlashMessage('Updated the meeting.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(Meeting $meeting) {
		$this->meetingRepository->remove($meeting);
		$this->addFlashMessage('Deleted a meeting.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function exportAction(Meeting $meeting) {
	}

}