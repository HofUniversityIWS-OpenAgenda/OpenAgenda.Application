<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use OpenAgenda\Application\Domain\Model\AgendaItem;
use OpenAgenda\Application\Domain\Model\Meeting;
use TYPO3\Flow\Annotations as Flow;

class AgendaItemController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\AgendaItemRepository
	 */
	protected $agendaItemRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $newAgendaItem
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function createAction(AgendaItem $newAgendaItem, Meeting $meeting) {
		$newAgendaItem->setCreationDate(new \DateTime());

		$newAgendaItem->setMeeting($meeting);
		$meeting->getAgendaItems()->add($newAgendaItem);

		$this->historyService->invoke($newAgendaItem);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @return void
	 */
	public function editAction(AgendaItem $agendaItem) {
		$this->view->assign('agendaItem', $agendaItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @return void
	 */
	public function updateAction(AgendaItem $agendaItem) {
		$this->agendaItemRepository->update($agendaItem);
		$this->historyService->invoke($agendaItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(AgendaItem $agendaItem, Meeting $meeting) {
		$meeting->getAgendaItems()->removeElement($agendaItem);

		$this->historyService->invoke($agendaItem);
		$this->historyService->invoke($meeting);
	}

}