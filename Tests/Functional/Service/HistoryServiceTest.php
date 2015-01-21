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
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $historyRepository;



	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->fixture = new \OpenAgenda\Application\Service\HistoryService();
		$this->historyRepository = new \OpenAgenda\Application\Domain\Repository\HistoryRepository;
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->fixture);
		unset($this->historyRepository);
	}

	/**
	 * @test
	 */
	public function isInvokeActionAppliedToHistoryModel() {
		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\SimpleEntity();

		$this->fixture->invoke($entity);
		$this->persistenceManager->persistAll();
		$this->assertNotNull($this->historyRepository->findAll()->getFirst());
	}

	/**
	 * @test
	 */
	public function invokeActionSetCorrectTypeToHistoryModel() {
		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\SimpleEntity();

		$this->fixture->invoke($entity);
		$this->persistenceManager->persistAll();
		$this->assertEquals(get_class($entity), $this->historyRepository->findAll()->getFirst()->getEntityType());
	}

	/**
	 * @test
	 */
	public function invokeActionAppliedToEntityService() {
		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\SimpleEntity();

		$this->fixture->invoke($entity);
		$this->persistenceManager->persistAll();
		$this->assertInstanceOf('DateTime', $entity->getModificationDate());
	}

}