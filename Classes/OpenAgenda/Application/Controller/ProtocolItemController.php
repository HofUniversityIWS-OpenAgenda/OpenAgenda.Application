<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\ProtocolItem;
use OpenAgenda\Application\Domain\Model\Meeting;

class ProtocolItemController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\ProtocolItemRepository
	 */
	protected $protocolItemRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $newProtocolItem
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function createAction(ProtocolItem $newProtocolItem, Meeting $meeting) {
		$newProtocolItem->setCreationDate(new \DateTime());

		$newProtocolItem->setMeeting($meeting);
		$meeting->getAgendaItems()->add($newProtocolItem);

		$this->historyService->invoke($newProtocolItem);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @return void
	 */
	public function editAction(ProtocolItem $protocolItem) {
		$this->view->assign('protocolItem', $protocolItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @return void
	 */
	public function updateAction(ProtocolItem $protocolItem) {
		$this->protocolItemRepository->update($protocolItem);
		$this->historyService->invoke($protocolItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(ProtocolItem $protocolItem, Meeting $meeting) {
		$meeting->getAgendaItems()->removeElement($protocolItem);
		
		$this->historyService->invoke($protocolItem);
		$this->historyService->invoke($meeting);
	}


}