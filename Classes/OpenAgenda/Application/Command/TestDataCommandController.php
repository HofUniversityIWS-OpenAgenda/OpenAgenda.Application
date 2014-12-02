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
use \OpenAgenda\Application\Domain\Model\Task;
use \OpenAgenda\Application\Domain\Model\Invitation;

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
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\InvitationRepository
	 */
	protected $invitationRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 * @Flow\Inject
	 */
	protected $accountFactory;


	/**
	 * @var \OpenAgenda\Application\Domain\Factory\PersonFactory
	 * @Flow\Inject
	 */
	protected $personFactory;

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

	/**
	 * ### meetings for testing ###
	 *
	 * This Command removes all existing meetings / AgendaItems and creates new meetings (default = 5) and new AgendaItems / ProtocolItems (default 3 for each) with dummy data to the DB.
	 *
	 * @param integer $quantity The quantity of new meetings
	 * @param integer $itemQuantity The quantity of new sub-items
	 * @param integer $invitations The quantity of Invitations
	 * @return string
	 */
	public function createMeetingsCommand($quantity = 5, $itemQuantity = 3, $invitations = 1) {
		$this->agendaItemRepository->removeAll();
		$this->protocolItemRepository->removeAll();
		$this->invitationRepository->removeAll();
		$this->meetingRepository->removeAll();

		$adminAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName('admin@openagenda.org', 'DefaultProvider');

		for($counter = 0;$counter < $quantity; $counter++){
			$newMeeting = new Meeting;
			$newMeeting->setCreationDate(new \DateTime());
			$newMeeting->setModificationDate($newMeeting->getCreationDate());
			$newMeeting->setScheduleStartDate(new \DateTime('2015-01-05 12:00'));
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

			for ($invitationCounter = 0; $invitationCounter < $invitations; $invitationCounter++) {
				$newInvitation = new Invitation();
				$newInvitation->setParticipant($adminAccount->getParty());
				$newInvitation->setStatus(Invitation::STATUS_OPEN);
				$newInvitation->setCreationDate(new \DateTime());
				$newInvitation->setModificationDate($newInvitation->getCreationDate());

				$newInvitation->setMeeting($newMeeting);
				//$newMeeting->getInvitations()->add($newInvitation);
			}

			$this->meetingRepository->add($newMeeting);
		}

		$this->response->appendContent('Created ' . $quantity . ' Meetings' . PHP_EOL);
		$this->response->appendContent('+ with each having ' . $itemQuantity . ' AgendaItems' . PHP_EOL);
		$this->response->appendContent('+ with each having ' . $itemQuantity . ' ProtocolItems' . PHP_EOL);
	}

	/**
	 * @param int $quantity Quantity of tasks to be created
	 */
	public function createTasksCommand($quantity = 5) {
		$this->taskRepository->removeAll();
		$adminAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName('admin@openagenda.org', 'DefaultProvider');

		for ($counter = 0; $counter < $quantity; $counter++) {
			$newTask = new Task();
			$newTask->setTitle('Task #' . ($counter + 1));
			$newTask->setDescription('Description #' . ($counter + 1));
			$newTask->setDueDate(new \DateTime());
			$newTask->setCreationDate(new \DateTime());
			$newTask->setModificationDate(new \DateTime());
			$newTask->setStatus(0);
			$newTask->setAssignee($adminAccount);
			$this->taskRepository->add($newTask);
		}

		$this->response->appendContent($quantity . ' tasks created' . PHP_EOL);
	}

	/**
	 * @param string $identifier Account identifier (default: 'admin@openagenda.org')
	 */
	public function createAdminUserCommand($identifier = 'admin@openagenda.org') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, 'DefaultProvider');

		if ($account === NULL) {
			$role = 'OpenAgenda.Application:Administrator';
			$account = $this->accountFactory->createAccountWithPassword($identifier, 'password', array($role));
			$this->accountRepository->add($account);
			$this->response->appendContent('Admin User "' . $identifier . '" created' . PHP_EOL);
		}

		if ($account->getParty() === NULL) {
			$person = $this->personFactory->createAnonymousPersonWithElectronicAddress($identifier);
			$account->setParty($person);
			$this->accountRepository->update($account);
			$this->response->appendContent('Admin Person "' . $identifier . '" created' . PHP_EOL);
		}

	}

}