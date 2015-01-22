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
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository
	 */
	protected $historyRepository;

	/**
	 * @var \OpenAgenda\Application\Tests\Unit\Service\Fixture\SimpleEntity |\PHPUnit_Framework_MockObject_MockObject
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

		$this->entity = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntityWithTitleAndDate();
		$this->entity->setTitle(uniqid('Title'));
		$this->entity->setDate(new \DateTime());

		$this->entityIdentifier = \TYPO3\Flow\Reflection\ObjectAccess::getProperty(
			$this->entityIdentifier,
			'Persistence_Object_Identifier',
			TRUE
		);

		$this->securityContextMock = $this->getMock(
			'TYPO3\\Flow\\Security\\Context',
			array('initialize'), array()
		);

		$this->entityServiceMock = $this->getMock(
			'OpenAgenda\\Application\\Service\\EntityService',
			array('applyStatusDates'), array()
		);

		$this->fixture = $this->getAccessibleMock(
			'OpenAgenda\\Application\\Service\\HistoryService',
			array('_none')
		);

		$this->fixture->_set('securityContext', $this->securityContextMock);
		$this->fixture->_set('entityService', $this->entityServiceMock);
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
	 * test
	 */
	public function invokeActionSetCorrectTypeToHistoryModel() {
		$this->fixture->invoke($this->entity);
		$this->persistenceManager->persistAll();
		$this->assertEquals(get_class($this->entity), $this->historyRepository->findAll()->getFirst()->getEntityType());
	}

	/**
	 * test
	 */
	public function invokeActionAppliedToEntityService() {
		$this->fixture->invoke($this->entity);
		$this->persistenceManager->persistAll();
		$this->assertInstanceOf('DateTime', $this->entity->getModificationDate());
	}

}