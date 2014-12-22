<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Note;

class NoteController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\NoteRepository
	 */
	protected $noteRepository;

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function showAction(Note $note) {
		$this->view->assign('value', $this->arrayService->flatten($note, 'show'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function updateAction(Note $note) {
		$this->historyService->invoke($note);
		$this->noteRepository->update($note);
	}

}