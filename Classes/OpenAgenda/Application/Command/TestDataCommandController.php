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
	 * This Command removes all meetings and creates a new meeting with dummy data to the DB.
	 * !!! Without AgendaItem and ProtocolItem !!!
	 *
	 * @return string
	 */
	public function addMeetingsCommand() {
		$dateNow = new \DateTime('now',  new \DateTimeZone( 'GMT+1' ));

		$this->meetingRepository->removeAll();

		new \Doctrine\Common\Collections\ArrayCollection();
		$newAgendaItem = new AgendaItem;
		$newAgendaItem->setCreationDate($creationDate);
		$newAgendaItem->setDescription($description);
		$newAgendaItem->setModificationDate($modificationDate);
		$newAgendaItem->setResources($resources);
		$newAgendaItem->setSorting($sorting);
		$newAgendaItem->setTitle("First AgendaItem");

		$newMeeting = new Meeting;
		//$newMeeting->setAgendaItems();
		$newMeeting->setEndDate(new \DateTime('2014-11-12 13:00'));
		$newMeeting->setModificationDate(new \DateTime('2014-11-11 10:00'));
		//$newMeeting->setProtocolItems();
		$newMeeting->setCreationDate($dateNow);
		$newMeeting->setStartDate(new \DateTime('2014-11-12 12:00'));
		$newMeeting->setStatus(1);
		$newMeeting->setTitle('First Meeting');

		$this->meetingRepository->add($newMeeting);
		return "Created a new meeting.";
	}

}