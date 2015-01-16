<?php
namespace OpenAgenda\Application\Tests\Unit\Service;

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
class HistoryServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

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
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->persistenceManagerMock = $this->getMock(
			'TYPO3\\Flow\\Persistence\\Generic\\PersistenceManager',
			array('persistAll'), array(), '', FALSE
		);

		$this->fixture = $this->getAccessibleMock(
			'OpenAgenda\\Application\\Controller\\MeetingController',
			array('_none')
		);

		$this->fixture->_set('persistenceManager', $this->persistenceManagerMock);
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->meetingRepositoryMock);
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function isInvokeActionAppliedToHistoryModel() {
		$this->markTestIncomplete('ToDo!');
//		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\Entity();

//		$this->fixture->invoke($entity);
//		$this->assertEquals(2, $entity->getStatus());
//		$this->assertInstanceOf($entity, $history->getEntityType());
	}

}