<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;

/**
 * Class MeetingController
 *
 * @package OpenAgenda\Application\Controller
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class MeetingController extends AbstractController {

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

	protected function initializeCreateAction() {
		if ($this->arguments->hasArgument('meeting')) {
			$this->initializePropertyMappingConfiguration('meeting');
		}
	}

	protected function initializeUpdateAction() {
		if ($this->arguments->hasArgument('meeting')) {
			$this->initializePropertyMappingConfiguration('meeting');
		}
	}

	protected function initializePropertyMappingConfiguration($propertyName) {
		$propertyMappingConfiguration = $this->arguments->getArgument($propertyName)->getPropertyMappingConfiguration();
		$propertyMappingConfiguration
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE)
			->allowAllProperties();
		$propertyMappingConfiguration->forProperty('agendaItems.*')
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE)
			->allowAllProperties();
		$propertyMappingConfiguration->forProperty('agendaItems.*.note')
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE)
			->allowAllProperties();
		$propertyMappingConfiguration->forProperty('invitations.*')
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE)
			->allowAllProperties();
		$propertyMappingConfiguration->forProperty('tasks.*')
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE)
			->allowAllProperties();
		$propertyMappingConfiguration->forProperty('tasks.*.assignee')
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED, TRUE)
			->allowAllProperties();
	}

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('meetings', $this->meetingRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function dashboardAction() {
	}

	/**
	 * @param Meeting $meeting
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function commitAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_COMMITTED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);

		foreach ($meeting->getInvitations() as $invitation) {
			$this->messagingService->prepareForPerson(
				$invitation->getParticipant(),
				'Meeting/Invite',
				array('invitation' => $invitation)
			);
		}

		$this->persistenceManager->persistAll();
		$this->view->assign('value', TRUE);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function startAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_STARTED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
		$this->persistenceManager->persistAll();
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function closeAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_CLOSED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
		$this->persistenceManager->persistAll();
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function cancelAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_CANCELED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
		$this->persistenceManager->persistAll();
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 * @deprecated Use Meeting::determineInvitationStatus() instead
	 */
	public function showStatusOfInvitationsAction(Meeting $meeting) {
		$this->view->assign('value', $meeting->determineInvitationStatus());
	}

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('value', $this->arrayService->flatten($this->meetingRepository->findAllowed(), 'list'));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function showAction(Meeting $meeting) {
		$this->view->assign('value', $this->arrayService->flatten($meeting, 'show'));
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function createAction(Meeting $meeting = NULL) {
		if ($meeting === NULL) {
			$this->redirect('new');
		}
		
		$person = $this->securityContext->getParty();
		$meeting->setCreator($person);
		$meeting->setStatus(Meeting::STATUS_CREATED);

		$this->entityService->applyStatusDates($meeting);
		$this->entityService->applySortingOrder($meeting->getAgendaItems());

		$this->historyService->invoke($meeting);
		$this->meetingRepository->add($meeting);
		$this->persistenceManager->persistAll();

		$this->view->assign('value', $this->arrayService->flatten($meeting));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function editAction(Meeting $meeting) {
		$this->view->assign('meeting', $meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function updateAction(Meeting $meeting) {
		foreach ($meeting->getAgendaItems() as $agendaItem) {
			if ($agendaItem->getNote() === NULL) {
				$note = new \OpenAgenda\Application\Domain\Model\Note();
				$agendaItem->setNote($note);
				$this->historyService->invoke($note);
				$this->historyService->invoke($agendaItem);
				$this->entityService->applyStatusDates($note);
			}
			$this->entityService->applyStatusDates($agendaItem);
		}

		foreach ($meeting->getInvitations() as $invitation) {
			$this->entityService->applyStatusDates($invitation);
		}

		foreach ($meeting->getTasks() as $task) {
			$this->entityService->applyStatusDates($task);
		}

		$this->historyService->invoke($meeting);
		$this->meetingRepository->update($meeting);
		$this->view->assign('value', TRUE);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(Meeting $meeting) {
		$result = FALSE;

		// Allow deletion only if meeting is created
		// and not invitations etc. have been sent, yet
		if ($meeting->getStatus() === Meeting::STATUS_CREATED) {
			$this->meetingRepository->remove($meeting);
			$this->persistenceManager->persistAll();
			$result = TRUE;
		}

		$this->view->assign('value', $result);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function exportAction(Meeting $meeting) {
	}

}