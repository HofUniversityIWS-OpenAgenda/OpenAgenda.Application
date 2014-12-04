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
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('value', $this->taskRepository->findAllowed()->toFlatArray('list'));
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
		$this->view->assign('value', $task->toFlatArray('show'));
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
		$newTask->setCreationDate(new \DateTime());
		$this->historyService->invoke($newTask);
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
		$this->historyService->invoke($task);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function deleteAction(Task $task) {
		$this->taskRepository->remove($task);
		$this->historyService->invoke($task);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function exportAction(Task $task) {
	}
}