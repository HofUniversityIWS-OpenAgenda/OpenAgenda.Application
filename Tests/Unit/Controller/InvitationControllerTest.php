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
class InvitationControllerTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = FALSE;

	/**
	 * @var \OpenAgenda\Application\Controller\InvitationController |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @var \TYPO3\Flow\Persistence\Generic\PersistenceManager|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $persistenceManagerMock;

	/**
	 * @var \OpenAgenda\Application\Domain\Repository\InvitationRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $invitationRepositoryMock;

	/**
	 * @var \OpenAgenda\Application\Service\HistoryService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $historyServiceMock;


	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->persistenceManagerMock = $this->getMock(
			'TYPO3\\Flow\\Persistence\\Generic\\PersistenceManager',
			array('persistAll'), array(), '', FALSE
		);

		$this->invitationRepositoryMock = $this->getMock(
			'OpenAgenda\\Application\\Domain\\Repository\\InvitationRepository',
			array('update'), array(), '', FALSE
		);
		$this->historyServiceMock = $this->getMock(
			'OpenAgenda\\Application\\Service\\HistoryService',
			array('invoke'), array(), '', FALSE
		);

		$this->fixture = $this->getAccessibleMock(
			'OpenAgenda\\Application\\Controller\\InvitationController',
			array('redirect')
		);

		$this->fixture->_set('persistenceManager', $this->persistenceManagerMock);
		$this->fixture->_set('invitationRepository', $this->invitationRepositoryMock);
		$this->fixture->_set('historyService', $this->historyServiceMock);
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->persistenceManagerMock);
		unset($this->invitationRepositoryMock);
		unset($this->historyServiceMock);
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function isAcceptActionAppliedToInvitationModel() {
		$invitation = new \OpenAgenda\Application\Tests\Unit\Controller\Fixture\SimpleInvitationModelWithStatus();

		$this->fixture->acceptAction($invitation);
		$this->assertEquals(1, $invitation->getStatus());
	}

	/**
	 * @test
	 */
	public function isDeclineActionAppliedToInvitationModel() {
		$invitation = new \OpenAgenda\Application\Tests\Unit\Controller\Fixture\SimpleInvitationModelWithStatus();

		$this->fixture->declineAction($invitation);
		$this->assertEquals(2, $invitation->getStatus());
	}

}