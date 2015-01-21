<?php
namespace OpenAgenda\Application\Tests\Functional\Controller;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Tests of TaskController.
 *
 * @group small
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class TaskControllerTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = FALSE;

	/**
	 * @var \OpenAgenda\Application\Controller\TaskController |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $taskRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $historyRepository;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meeting;

	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->fixture = new \OpenAgenda\Application\Controller\TaskController;
		$this->taskRepository = new \OpenAgenda\Application\Domain\Repository\TaskRepository;
		$this->meeting = new \OpenAgenda\Application\Domain\Model\Meeting;
		$this->historyRepository = new \OpenAgenda\Application\Domain\Repository\HistoryRepository;

//		$this->persistenceManager->persistAll();
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {

		unset($this->fixture);
		unset($this->taskRepository);
		unset($this->meeting);
		unset($this->historyRepository);
	}

	/**
	 * @test
	 */
	public function listActionReturnsTaskJsonInBrowser() {

		$response = $this->browser->request('http://flow.localhost/task/list.json');
		$this->assertEquals(200, $response->getStatusCode());
	}

	/**
	 * @test
	 */
	public function isCreateActionAppliedToHistoryService() {
		$this->fixture->createAction($this->meeting, $this->taskRepository->findAll()->getFirst());
		$this->persistenceManager->persistAll();

		$this->assertNotNull($this->historyRepository->findAll()->getFirst());
	}

}