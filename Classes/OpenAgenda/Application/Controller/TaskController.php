<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;
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
		$this->view->assign('value', $this->arrayService->flatten($this->taskRepository->findAll(), 'list'));
	}

	/**
	 * @return void
	 */
	public function listMineAction() {
		$this->view->assign('value', $this->arrayService->flatten($this->taskRepository->findByPerson(), 'list'));
	}

	/**
	 * @return void
	 */
	public function listOthersAction() {
		$this->view->assign('value', $this->arrayService->flatten($this->taskRepository->findAllowed(NULL, TRUE), 'list'));
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
		$this->view->assign('value', $this->arrayService->flatten($task, 'show'));
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function createAction(Meeting $meeting, Task $task) {
		$meeting->getTasks()->add($task);
		$this->entityService->applyStatusDates($task);
		$this->historyService->invoke($task);
		$this->historyService->invoke($meeting);
		$this->taskRepository->add($task);
		$this->view->assign('value', $this->arrayService->flatten($task, 'show'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function updateAction(Task $task) {
		$this->taskRepository->update($task);
		$this->historyService->invoke($task);
		$this->view->assign('value', $this->arrayService->flatten($task, 'show'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function deleteAction(Meeting $meeting, Task $task) {
		$meeting->getTasks()->remove($task);
		$this->taskRepository->remove($task);
		$this->persistenceManager->persistAll();
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Task $task
	 * @return void
	 */
	public function exportAction(Task $task) {
	}

}