<?php
namespace OpenAgenda\Application\Tests\Functional\Service\Export;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group large
 */
class DocumentServiceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = FALSE;

	/**
	 * @var \OpenAgenda\Application\Service\Export\DocumentService
	 */
	protected $fixture;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->fixture = new \OpenAgenda\Application\Service\Export\DocumentService();
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function agendaIsExported() {
		$this->fixture->exportAgenda($this->getMeeting());
	}

	/**
	 * @test
	 */
	public function protocolIsExported() {
		$this->fixture->exportProtocol($this->getMeeting());
	}

	/**
	 * @return \OpenAgenda\Application\Domain\Model\Meeting
	 */
	protected function getMeeting() {
		$somePerson = new \OpenAgenda\Application\Domain\Model\Person();
		$somePersonName = new \TYPO3\Party\Domain\Model\PersonName();
		$somePersonName->setFirstName('Dummy FirstName');
		$somePersonName->setLastName('Dummy LastName');
		$somePerson->setName($somePersonName);

		$meeting = new \OpenAgenda\Application\Domain\Model\Meeting();
		$meeting->setTitle('Test Meeting');
		$meeting->setLocation('Test Location');
		$meeting->setScheduledStartDate(new \DateTime());
		$meeting->setMinuteTaker($somePerson);

		$firstAgendaItem = new \OpenAgenda\Application\Domain\Model\AgendaItem();
		$firstAgendaItem->setTitle('First AgendaItem');
		$firstAgendaItem->setDescription('Description for First AgendaItem');
		$firstNote = new \OpenAgenda\Application\Domain\Model\Note();
		$firstNote->setDescription('Description for First Agenda Item');
		$firstAgendaItem->setNote($firstNote);
		$secondAgendaItem = new \OpenAgenda\Application\Domain\Model\AgendaItem();
		$secondAgendaItem->setTitle('Second AgendaItem');
		$secondAgendaItem->setDescription('Description for Second AgendaItem');
		$secondNote = new \OpenAgenda\Application\Domain\Model\Note();
		$secondNote->setDescription('Description for Second Agenda Item');
		$secondAgendaItem->setNote($secondNote);

		$meeting->getAgendaItems()->add($firstAgendaItem);
		$meeting->getAgendaItems()->add($secondAgendaItem);

		$firstInvitation = new \OpenAgenda\Application\Domain\Model\Invitation();
		$firstInvitation->setStatus(\OpenAgenda\Application\Domain\Model\Invitation::STATUS_COMMITTED);
		$firstInvitation->setParticipant($somePerson);
		$firstInvitation->setAvailable(TRUE);
		$secondInvitation = new \OpenAgenda\Application\Domain\Model\Invitation();
		$secondInvitation->setStatus(\OpenAgenda\Application\Domain\Model\Invitation::STATUS_CANCELED);
		$secondInvitation->setParticipant($somePerson);
		$secondInvitation->setAvailable(FALSE);
		$thirdInvitation = new \OpenAgenda\Application\Domain\Model\Invitation();
		$thirdInvitation->setStatus(\OpenAgenda\Application\Domain\Model\Invitation::STATUS_OPEN);
		$thirdInvitation->setParticipant($somePerson);
		$thirdInvitation->setAvailable(TRUE);
		$meeting->getInvitations()->add($firstInvitation);
		$meeting->getInvitations()->add($secondInvitation);
		$meeting->getInvitations()->add($thirdInvitation);

		$firstTask = new \OpenAgenda\Application\Domain\Model\Task();
		$firstTask->setTitle('First Task');
		$firstTask->setDescription('First Task Description');
		$firstTask->setAssignee($somePerson);
		$firstTask->setDueDate(new \DateTime());
		$secondTask = new \OpenAgenda\Application\Domain\Model\Task();
		$secondTask->setTitle('First Task');
		$secondTask->setDescription('First Task Description');
		$secondTask->setAssignee($somePerson);
		$secondTask->setDueDate(new \DateTime());
		$meeting->getTasks()->add($firstTask);
		$meeting->getTasks()->add($secondTask);

		return $meeting;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	protected function getTargetFilePath($name) {
		 return FLOW_PATH_DATA . str_replace('\\', '', __CLASS__) . '_' . $name;
	}

}
