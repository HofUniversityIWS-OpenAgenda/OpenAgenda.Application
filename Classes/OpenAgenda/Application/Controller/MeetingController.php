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
	public function indexAction() {
		$this->view->assign('meetings', $this->meetingRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function dashboardAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function startAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_STARTED);
		$meeting->setModificationDate(new \DateTime());
		$this->meetingRepository->update($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function closeAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_CLOSED);
		$meeting->setModificationDate(new \DateTime());
		$this->meetingRepository->update($meeting);
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
	public function createAction(Meeting $newMeeting = NULL) {
		if ($newMeeting === NULL) {
			$this->redirect('new');
		}

		$newMeeting->setCreationDate(new \DateTime());
		$newMeeting->setModificationDate($newMeeting->getCreationDate());
		$newMeeting->setStatus(Meeting::STATUS_CREATED);

		$this->meetingRepository->add($newMeeting);
		$this->redirect('\OpenAgenda\Application\Resources\Templates\Dashboard\Index');
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
		$meeting->setModificationDate(new \DateTime());
		$this->meetingRepository->update($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(Meeting $meeting) {
		$this->meetingRepository->remove($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function exportAction(Meeting $meeting) {
	}

}