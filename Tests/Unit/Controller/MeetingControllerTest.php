<?php
namespace OpenAgenda\Application\Tests\Unit\Controller;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Tests of MeetingController.
 *
 * @group small
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class MeetingControllerTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = FALSE;

	/**
	 * @var \OpenAgenda\Application\Controller\MeetingController |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @var \TYPO3\Flow\Persistence\Generic\PersistenceManager|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $persistenceManagerMock;

	/**
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meetingRepositoryMock;

	/**
	 * @var \OpenAgenda\Application\Service\HistoryService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $historyServiceMock;

	/**
	 * @var \OpenAgenda\Application\Service\Export\DocumentService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $documentServiceMock;

	/**
	 * @var \OpenAgenda\Application\Service\Communication\MessagingService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $messagingServiceMock;

	/**
	 * @var \OpenAgenda\Application\Tests\Unit\Controller\Fixture\View |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $view;



	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->persistenceManagerMock = $this->getMock(
			'TYPO3\\Flow\\Persistence\\Generic\\PersistenceManager',
			array('persistAll'), array(), '', FALSE
		);

		$this->meetingRepositoryMock = $this->getMock(
			'OpenAgenda\\Application\\Domain\\Repository\\MeetingRepository',
			array('update'), array(), '', FALSE
		);
		$this->view = $this->getMock(
			'OpenAgenda\\Application\\Tests\\Unit\\Controller\\Fixture\\View',
			array('assign'), array(), '', FALSE
		);
		$this->historyServiceMock = $this->getMock(
			'OpenAgenda\\Application\\Service\\HistoryService',
			array('invoke'), array(), '', FALSE
		);
		$this->documentServiceMock = $this->getMock(
			'OpenAgenda\\Application\\Service\\Export\\DocumentService',
			array('exportAgenda', 'exportProtocol'), array(), '', FALSE
		);
		$this->messagingServiceMock = $this->getMock(
			'OpenAgenda\\Application\\Service\\Communication\\MessagingService',
			array('prepareForPerson'), array(), '', FALSE
		);

		$this->fixture = $this->getAccessibleMock(
			'OpenAgenda\\Application\\Controller\\MeetingController',
			array('_none')
		);

		$this->fixture->_set('view', $this->view);
		$this->fixture->_set('persistenceManager', $this->persistenceManagerMock);
		$this->fixture->_set('meetingRepository', $this->meetingRepositoryMock);
		$this->fixture->_set('historyService', $this->historyServiceMock);
		$this->fixture->_set('documentService', $this->documentServiceMock);
		$this->fixture->_set('messagingService', $this->messagingServiceMock);
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->view);
		unset($this->persistenceManagerMock);
		unset($this->meetingRepositoryMock);
		unset($this->historyServiceMock);
		unset($this->documentServiceMock);
		unset($this->messagingServiceMock);
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function isStartActionAppliedToMeetingModel() {
		$meeting = new \OpenAgenda\Application\Tests\Unit\Controller\Fixture\SimpleMeetingModelWithInitValue();

		$this->fixture->startAction($meeting);
		$this->assertEquals(2, $meeting->getStatus());
		$this->assertInstanceOf('DateTime', $meeting->getStartDate());
	}

	/**
	 * @test
	 */
	public function isCommitActionAppliedToMeetingModel() {
		$meeting = new \OpenAgenda\Application\Tests\Unit\Controller\Fixture\SimpleMeetingModelWithInitValue();

		$this->fixture->commitAction($meeting);
		$this->assertEquals(1, $meeting->getStatus());
	}

	/**
	 * @test
	 */
	public function isCloseActionAppliedToMeetingModel() {
		$meeting = new \OpenAgenda\Application\Tests\Unit\Controller\Fixture\SimpleMeetingModelWithInitValue();

		$this->fixture->closeAction($meeting);
		$this->assertEquals(3, $meeting->getStatus());
		$this->assertInstanceOf('DateTime', $meeting->getEndDate());
	}

	/**
	 * @test
	 */
	public function isCancelActionAppliedToMeetingModel() {
		$meeting = new \OpenAgenda\Application\Tests\Unit\Controller\Fixture\SimpleMeetingModelWithInitValue();

		$this->fixture->cancelAction($meeting);
		$this->assertEquals(4, $meeting->getStatus());
	}
}