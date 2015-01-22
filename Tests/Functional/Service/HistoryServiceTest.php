<?php
namespace OpenAgenda\Application\Tests\Functional\Service;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group small
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class HistoryServiceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \OpenAgenda\Application\Service\HistoryService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository
	 */
	protected $historyRepository;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting
	 */
	protected $entity;

	/**
	 * @var string
	 */
	protected $entityIdentifier;

	/**
	 * @var \OpenAgenda\Application\Service\EntityService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $entityServiceMock;

	/**
	 * @var \TYPO3\Flow\Security\Context |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $securityContextMock;


	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->historyRepository = $this->objectManager->get(
			'OpenAgenda\\Application\\Domain\\Repository\\HistoryRepository'
		);

		$this->entity = new \OpenAgenda\Application\Domain\Model\Meeting();
		$this->entity->setTitle(uniqid('Title'));

		$this->entityIdentifier = \TYPO3\Flow\Reflection\ObjectAccess::getProperty(
			$this->entity,
			'Persistence_Object_Identifier',
			TRUE
		);

		$this->securityContextMock = $this->getMock(
			'TYPO3\\Flow\\Security\\Context',
			array('isInitialized'), array()
		);

		$this->objectManager->setInstance(
			'TYPO3\\Flow\\Security\\Context',
			$this->securityContextMock
		);

		$this->fixture = new \OpenAgenda\Application\Service\HistoryService();
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->entity);
		unset($this->fixture);
		unset($this->historyRepository);
		unset($this->securityContextMock);
	}

	/**
	 * @test
	 */
	public function isInvokeActionAppliedToHistoryModel() {
		$this->fixture->invoke($this->entity);
		$this->persistenceManager->persistAll();
		$this->assertNotNull($this->historyRepository->findAll()->getFirst());
	}

	/**
	 * @test
	 */
	public function invokeActionSetCorrectTypeToHistoryModel() {
		$this->fixture->invoke($this->entity);
		$this->persistenceManager->persistAll();
		$this->assertEquals(get_class($this->entity), $this->historyRepository->findByEntityIdentifier($this->entityIdentifier)->getFirst()->getEntityType());
	}

	/**
	 * @test
	 */
	public function invokeActionAppliedToEntityService() {
		$this->fixture->invoke($this->entity);
		$this->persistenceManager->persistAll();
		$this->assertInstanceOf('DateTime', $this->entity->getModificationDate());
	}

}