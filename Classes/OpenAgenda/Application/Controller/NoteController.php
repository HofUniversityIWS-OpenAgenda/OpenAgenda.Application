<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;
use OpenAgenda\Application\Domain\Model\Note;

class NoteController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\NoteRepository
	 */
	protected $noteRepository;

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('value', $this->arrayService->flatten($this->noteRepository->findAll(), 'list'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function showAction(Note $note) {
		$this->view->assign('value', $this->arrayService->flatten($note, 'show'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @param \OpenAgenda\Application\Domain\Model\Note $newNote
	 * @return void
	 */
	public function createAction(Meeting $meeting, Note $newNote) {
		$newNote->setCreationDate(new \DateTime());
		$this->historyService->invoke($newNote);

		$meeting->getProtocolItems()->add($newNote);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function updateAction(Note $note) {
		$this->historyService->invoke($note);
		$this->noteRepository->update($note);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function deleteAction(Meeting $meeting, Note $note) {
		$this->historyService->invoke($note);
		$this->historyService->invoke($meeting);

		$meeting->getProtocolItems()->removeElement($note);
	}

}