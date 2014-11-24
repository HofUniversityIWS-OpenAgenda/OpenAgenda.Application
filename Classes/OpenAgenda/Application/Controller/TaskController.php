<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Task;

class TaskController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @return void
	 */
	public function listAction() {
	}

	/**
	 * @return void
	 */
	public function dashboardAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function showAction(Task $task) {
		$this->view->assign('task', $task);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $newTask
	 * @return void
	 */
	public function createAction(Task $newTask) {
		$this->taskRepository->add($newTask);
		$this->addFlashMessage('Created a new task.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function editAction(Task $task) {
		$this->view->assign('task', $task);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function updateAction(Task $task) {
		$this->taskRepository->update($task);
		$this->addFlashMessage('Updated the task.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function deleteAction(Task $task) {
		$this->taskRepository->remove($task);
		$this->addFlashMessage('Deleted a task.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function exportAction(Task $task) {
	}
}