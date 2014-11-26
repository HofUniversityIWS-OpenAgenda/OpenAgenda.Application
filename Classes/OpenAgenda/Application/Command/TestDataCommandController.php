<?php
namespace OpenAgenda\Application\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use \OpenAgenda\Application\Domain\Model\Meeting;
use \OpenAgenda\Application\Domain\Model\AgendaItem;
use \OpenAgenda\Application\Domain\Model\ProtocolItem;

/**
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class TestDataCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository
	 */
	protected $meetingRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\AgendaItemRepository
	 */
	protected $agendaItemRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\ProtocolItemRepository
	 */
	protected $protocolItemRepository;

	/**
	 * ### meetings for testing ###
	 *
	 * This Command removes all existing meetings / AgendaItems and creates new meetings (default = 5) and new AgendaItems / ProtocolItems (default 3 for each) with dummy data to the DB.
	 *
	 * @param integer $quantity The quantity of new meetings
	 * @param integer $itemQuantity The quantity of new sub-items
	 * @return string
	 */
	public function createMeetingsCommand($quantity = 5, $itemQuantity = 3) {
		$this->agendaItemRepository->removeAll();
		$this->protocolItemRepository->removeAll();
		$this->meetingRepository->removeAll();

		for($counter = 0;$counter < $quantity; $counter++){
			$newMeeting = new Meeting;
			$newMeeting->setCreationDate(new \DateTime());
			$newMeeting->setModificationDate($newMeeting->getCreationDate());
			$newMeeting->setStartDate(new \DateTime('2015-01-05 12:00'));
			$newMeeting->setStatus(Meeting::STATUS_CREATED);
			$newMeeting->setTitle('Meeting '.($counter+1));

			for ($itemCounter = 0; $itemCounter < $itemQuantity; $itemCounter++) {
				$newAgendaItem = new AgendaItem();
				$newAgendaItem->setCreationDate(new \DateTime());
				$newAgendaItem->setModificationDate($newAgendaItem->getCreationDate());
				$newAgendaItem->setTitle('Item #' . ($itemCounter + 1));
				$newAgendaItem->setDescription('Description #' . ($itemCounter + 1));
				$newAgendaItem->setSorting($itemCounter + 1);

				$newAgendaItem->setMeeting($newMeeting);
				$newMeeting->getAgendaItems()->add($newAgendaItem);
			}

			for ($itemCounter = 0; $itemCounter < $itemQuantity; $itemCounter++) {
				$newProtocolItem = new ProtocolItem();
				$newProtocolItem->setSorting($itemCounter + 1);

				$newProtocolItem->setMeeting($newMeeting);
				$newMeeting->getProtocolItems()->add($newProtocolItem);
			}

			$this->meetingRepository->add($newMeeting);
		}
		return "Created ".$quantity." Meetings and ".$itemQuantity." AgendaItems + ".$itemQuantity." ProtocolItems for each Meeting.";
	}

}