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
	 * ### meetings for testing ###
	 *
	 * This Command removes all existing meetings and creates new meetings (default = 5) with dummy data to the DB.
	 * !!! Without AgendaItem and ProtocolItem !!!
	 *
	 * @param integer $quantity The quantity of new meetings
	 * @param integer $itemQuantity The quantity of new sub-items
	 * @return string
	 */
	public function createMeetingsCommand($quantity = 5, $itemQuantity = 3) {
		$dateNow = new \DateTime('now',  new \DateTimeZone( 'GMT+1' ));
		$this->meetingRepository->removeAll();

		for($counter = 0;$counter < $quantity; $counter++){

			$newMeeting = new Meeting;
			//$newMeeting->setAgendaItems();
			//$newMeeting->setEndDate(new \DateTime('2014-11-12 13:00'));
			//$newMeeting->setModificationDate(new \DateTime('2014-11-11 10:00'));
			//$newMeeting->setProtocolItems();
			$newMeeting->setCreationDate($dateNow);
			$newMeeting->setStartDate(new \DateTime('2014-11-12 12:00'));
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

			$this->meetingRepository->add($newMeeting);
		}
		return "Created ".$quantity." meetings.";
	}

}