<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\Note;

class NoteController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\NoteRepository
	 */
	protected $noteRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('notes', $this->noteRepository->findAll());
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function showAction(Note $note) {
		$this->view->assign('note', $note);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $newNote
	 * @return void
	 */
	public function createAction(Note $newNote) {
		$this->noteRepository->add($newNote);
		$this->addFlashMessage('Created a new note.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function editAction(Note $note) {
		$this->view->assign('note', $note);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function updateAction(Note $note) {
		$this->noteRepository->update($note);
		$this->addFlashMessage('Updated the note.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Note $note
	 * @return void
	 */
	public function deleteAction(Note $note) {
		$this->noteRepository->remove($note);
		$this->addFlashMessage('Deleted a note.');
		$this->redirect('index');
	}

}