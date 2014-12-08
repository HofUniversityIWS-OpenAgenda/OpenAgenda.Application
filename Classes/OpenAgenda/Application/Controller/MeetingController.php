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
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

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
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function closeAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_CLOSED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function showStatusOfInvitationsAction(Meeting $meeting) {
		$invitationsStatusCounter = array(
			'open' => 0,
			'committed' => 0,
			'canceled' => 0,
		);

		foreach ($meeting->getInvitations() as $invitation){
			if($invitation->getStatus() === 0) {
				$invitationsStatusCounter['open']++;
			}  else if($invitation->getStatus() === 1){
				$invitationsStatusCounter['committed']++;
			} else if($invitation->getStatus() === 2){
				$invitationsStatusCounter['canceled']++;
			}
		}

		$this->view->assign('value', $invitationsStatusCounter);
	}

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('value', $this->arrayService->flatten($this->meetingRepository->findAllowed(), 'list'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function showAction(Meeting $meeting) {
		$this->view->assign('value', $this->arrayService->flatten($meeting, 'show'));
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
		$newMeeting->setStatus(Meeting::STATUS_CREATED);

		$this->meetingRepository->add($newMeeting);
		$this->historyService->invoke($newMeeting);
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
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(Meeting $meeting) {
		$this->meetingRepository->remove($meeting);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function exportAction(Meeting $meeting) {
	}

}