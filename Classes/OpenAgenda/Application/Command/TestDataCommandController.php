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
	 * @var \OpenAgenda\Application\Domain\Repository\InvitationRepository
	 */
	protected $invitationRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\NoteRepository
	 */
	protected $noteRepository;

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
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository
	 * @Flow\Inject
	 */
	protected $historyRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;
	/**
	 * ### meetings for testing ###
	 *
	 * This Command removes all existing meetings / AgendaItems and creates new meetings (default = 5) and new AgendaItems / ProtocolItems (default 3 for each) / Invitations (default 1) with dummy data to the DB.
	 *
	 * @param integer $quantity The quantity of new meetings
	 * @param integer $itemQuantity The quantity of new sub-items (notes and tasks)
	 * @param integer $invitations The quantity of Invitations
	 * @return string
	 */
	public function createMeetingsCommand($quantity = 5, $itemQuantity = 3, $invitations = 1) {
		$this->meetingRepository->removeAll();
		$this->taskRepository->removeAll();
		$this->agendaItemRepository->removeAll();
		$this->noteRepository->removeAll();
		$this->invitationRepository->removeAll();

		$adminAccount = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName('admin@openagenda.org', 'DefaultProvider');

		for($counter = 0;$counter < $quantity; $counter++){
			$newMeeting = new Meeting;
			$newMeeting->setCreationDate(new \DateTime());
			$newMeeting->setModificationDate($newMeeting->getCreationDate());
			$newMeeting->setScheduledStartDate(new \DateTime('2015-01-05 12:00'));
			$newMeeting->setStatus(Meeting::STATUS_CREATED);
			$newMeeting->setTitle('Meeting '.($counter+1));
			$this->historyService->invoke($newMeeting);

			for ($itemCounter = 0; $itemCounter < $itemQuantity; $itemCounter++) {
				$newAgendaItem = new AgendaItem();
				$newAgendaItem->setCreationDate(new \DateTime());
				$newAgendaItem->setModificationDate($newAgendaItem->getCreationDate());
				$newAgendaItem->setTitle('Item #' . ($itemCounter + 1));
				$newAgendaItem->setDescription('Description #' . ($itemCounter + 1));
				$newAgendaItem->setSorting($itemCounter + 1);

				$newNote = new \OpenAgenda\Application\Domain\Model\Note();
				$newNote->setCreationDate(new \DateTime());
				$newNote->setDescription('Description for Meeting #' . ($itemCounter + 1));
				$this->historyService->invoke($newNote);
				$newAgendaItem->setNote($newNote);

				$newAgendaItem->setMeeting($newMeeting);
				$newMeeting->getAgendaItems()->add($newAgendaItem);
				$this->historyService->invoke($newAgendaItem);
			}

			for ($itemCounter = 0; $itemCounter < $itemQuantity; $itemCounter++) {
				$newTask = new \OpenAgenda\Application\Domain\Model\Task();
				$newTask->setMeeting($newMeeting);
				$newTask->setCreationDate(new \DateTime());
				$newTask->setTitle('Task #' . ($itemCounter + 1));
				$newTask->setDescription('Description #' . ($itemCounter + 1));
				$newTask->setDueDate(new \DateTime());
				$newTask->setStatus(0);
				$newTask->setAssignee($adminAccount->getParty());
				$this->historyService->invoke($newTask);
				$newMeeting->getTasks()->add($newTask);
			}

			for ($invitationCounter = 0; $invitationCounter < $invitations; $invitationCounter++) {
				$newInvitation = new Invitation();
				$newInvitation->setParticipant($adminAccount->getParty());
				$newInvitation->setStatus(Invitation::STATUS_OPEN);
				$newInvitation->setCreationDate(new \DateTime());
				$newInvitation->setModificationDate($newInvitation->getCreationDate());

				$newInvitation->setMeeting($newMeeting);
				$newMeeting->getInvitations()->add($newInvitation);
				$this->historyService->invoke($newInvitation);
			}

			$this->meetingRepository->add($newMeeting);
		}

		$this->response->appendContent('Created ' . $quantity . ' Meetings' . PHP_EOL);
		$this->response->appendContent('+ with each having ' . $itemQuantity . ' AgendaItems' . PHP_EOL);
		$this->response->appendContent('+ with each having ' . $itemQuantity . ' Notes' . PHP_EOL);
		$this->response->appendContent('+ with each having ' . $itemQuantity . ' Task' . PHP_EOL);
		$this->response->appendContent('+ with each having ' . $invitations . ' Invitations' . PHP_EOL);
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
			$this->historyService->invoke($newTask);
		}

		$this->response->appendContent($quantity . ' tasks created' . PHP_EOL);
	}

	/**
	 * @param string $identifier Account identifier (default: 'admin@openagenda.org')
	 * @param string $role Role (default: 'Administrator')
	 * @param string $firstname First name (default: 'Mark')
	 * @param string $lastname Last name(default: 'Mabuse')
	 */
	public function createUserCommand($identifier = 'admin@openagenda.org', $role = 'Administrator', $firstname = 'Mark', $lastname = 'Mabuse') {
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, 'DefaultProvider');

		if ($account !== NULL){
			$this->accountRepository->remove($this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($identifier, 'DefaultProvider'));
			$this->persistenceManager->persistAll();
		}

		$roleString = 'OpenAgenda.Application:' . $role;
		$account = $this->accountFactory->createAccountWithPassword($identifier, 'password', array($roleString));
		$this->accountRepository->add($account);
		$this->response->appendContent($role . ' User "' . $identifier . '" created' . PHP_EOL);

		if ($account->getParty() === NULL) {
			$person = $this->personFactory->createPerson($identifier, $firstname, $lastname);
			$account->setParty($person);
			$this->accountRepository->update($account);
			$this->response->appendContent($role . ' Person "' . $identifier . '" created' . PHP_EOL);
		}
	}

	/**
	 * Create different Users
	 */
	public function createUsersCommand() {
		$this->createUserCommand();
		$this->createUserCommand('listener@openagenda.org', 'Listener', 'Franz', 'List');
		$this->createUserCommand('participant1@openagenda.org', 'Participant', 'Elen', 'Kutis');
		$this->createUserCommand('participant2@openagenda.org', 'Participant', 'Monika', 'Moneypenny');
		$this->createUserCommand('participant3@openagenda.org', 'Participant', 'Ric', 'de Stunt');
		$this->createUserCommand('minutetaker@openagenda.org', 'MinuteTaker', 'Manuel', 'Tacker');
		$this->createUserCommand('meetingchair@openagenda.org', 'MeetingChair', 'Martin', 'MeeChair');
		$this->createUserCommand('meetingmanager@openagenda.org', 'MeetingManager', 'Martin', 'Manager');
		$this->createUserCommand('chairman@openagenda.org', 'Chairman', 'Christian', 'Chairman');
	}

	/**
	 * @param boolean $sureToDeleteFlag Are you sure you want to delete the complete History?
	 */
	public function clearHistoryCommand($sureToDeleteFlag = false){
		if($sureToDeleteFlag === true) {
			$this->historyRepository->removeAll();
			$this->response->appendContent('Cleared History' . PHP_EOL);
		}else{
			$this->response->appendContent('History was not cleared. Please set the flag.' . PHP_EOL);
		}
	}

}