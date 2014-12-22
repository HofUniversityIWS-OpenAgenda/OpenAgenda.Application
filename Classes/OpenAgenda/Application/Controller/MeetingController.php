<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;

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
		if ($this->arguments->hasArgument('newMeeting')) {
			$this->initializePropertyMappingConfiguration('newMeeting');
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
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function startAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_STARTED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function closeAction(Meeting $meeting) {
		$meeting->setStatus(Meeting::STATUS_CLOSED);
		$this->meetingRepository->update($meeting);
		$this->historyService->invoke($meeting);
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
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $newMeeting
	 * @return void
	 */
	public function createAction(Meeting $newMeeting = NULL) {
		if ($newMeeting === NULL) {
			$this->redirect('new');
		}

		$newMeeting->setStatus(Meeting::STATUS_CREATED);
		$this->entityService->applyStatusDates($newMeeting);
		$this->entityService->applySortingOrder($newMeeting->getAgendaItems());

		$this->historyService->invoke($newMeeting);
		$this->meetingRepository->add($newMeeting);
		$this->persistenceManager->persistAll();

		$this->view->assign('value', $this->arrayService->flatten($newMeeting));
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
		$this->historyService->invoke($meeting);
		$this->meetingRepository->update($meeting);
		$this->view->assign('value', TRUE);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function deleteAction(Meeting $meeting) {
		$agendaItems = $this->agendaItemRepository->findByMeeting($meeting);
		$invitations = $this->invitationRepository->findByMeeting($meeting);
		$tasks = $this->taskRepository->findByMeeting($meeting);

		foreach ($agendaItems as $removeObjects) {
			$this->agendaItemRepository->remove($removeObjects);
		}

		foreach ($invitations as $removeObjects) {
			$this->invitationRepository->remove($removeObjects);
		}

		foreach ($tasks as $removeObjects) {
			$this->taskRepository->remove($removeObjects);
		}
		$this->persistenceManager->persistAll();

		$this->historyService->invoke($meeting);
		$this->meetingRepository->remove($meeting);
		$this->view->assign('value', TRUE);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return void
	 */
	public function exportAction(Meeting $meeting) {
	}

}