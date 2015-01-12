<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use OpenAgenda\Application\Domain\Model\AgendaItem;
use OpenAgenda\Application\Domain\Model\Meeting;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class AgendaItemController
 *
 * @package OpenAgenda\Application\Controller
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class AgendaItemController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\AgendaItemRepository
	 */
	protected $agendaItemRepository;

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function createAction(AgendaItem $agendaItem, Meeting $meeting) {
		$note = $agendaItem->getNote();

		if ($note === NULL) {
			$note = new \OpenAgenda\Application\Domain\Model\Note();
			$agendaItem->setNote($note);
		}

		$this->entityService->applyStatusDates($agendaItem);
		$this->entityService->applyStatusDates($note);

		$this->historyService->invoke($agendaItem);
		$this->historyService->invoke($meeting);
		$this->historyService->invoke($note);

		$agendaItem->setMeeting($meeting);
		$meeting->getAgendaItems()->add($agendaItem);

		$this->agendaItemRepository->add($agendaItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @return void
	 * @deprecated This action is not used
	 */
	public function editAction(AgendaItem $agendaItem) {
		$this->view->assign('agendaItem', $agendaItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @return void
	 */
	public function updateAction(AgendaItem $agendaItem) {
		$this->historyService->invoke($agendaItem);
		$this->agendaItemRepository->update($agendaItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\AgendaItem $agendaItem
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(AgendaItem $agendaItem, Meeting $meeting) {
		$this->historyService->invoke($agendaItem);
		$this->historyService->invoke($meeting);

		$meeting->getAgendaItems()->removeElement($agendaItem);
		$this->agendaItemRepository->remove($agendaItem);
	}

}