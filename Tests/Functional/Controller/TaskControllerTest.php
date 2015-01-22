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
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \OpenAgenda\Application\Controller\TaskController |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository
	 */
	protected $historyRepository;

	/**
	 * @var \OpenAgenda\Application\Service\HistoryService
	 */
	protected $historyService;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meetingMock;

	/**
	 * @var \OpenAgenda\Application\Service\EntityService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $entityServiceMock;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Task |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $task;

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

		$this->taskRepository = $this->getMock(
			'OpenAgenda\\Application\\Domain\\Repository\\TaskRepository',
			array('findAll', 'add'), array()
		);

		$this->objectManager->setInstance(
			'OpenAgenda\\Application\\Domain\\Repository\\TaskRepository',
			$this->taskRepository
		);

		$this->securityContextMock = $this->getMock(
			'TYPO3\\Flow\\Security\\Context',
			array('isInitialized'), array()
		);

		$this->objectManager->setInstance(
			'TYPO3\\Flow\\Security\\Context',
			$this->securityContextMock
		);

		$this->fixture = new \OpenAgenda\Application\Controller\TaskController();

		$this->meetingMock = new \OpenAgenda\Application\Domain\Model\Meeting();

		$this->task = new \OpenAgenda\Application\Domain\Model\Task();
		$this->task->setCreationDate(new \DateTime());
		$this->task->setTitle(uniqid('Title'));

	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {

		unset($this->fixture);
		unset($this->taskRepository);
		unset($this->historyRepository);
		unset($this->meetingMock);
		unset($this->task);
	}

	/**
	 * @test
	 */
	public function listActionReturnsTaskJsonInBrowser() {

		$queryResult = array($this->task);

		$this->taskRepository
			->expects($this->once())
			->method('findAll')
			->will($this->returnValue($queryResult));

		$response = $this->browser->request('http://flow.localhost/task/list.json');
		$this->assertEquals(200, $response->getStatusCode());

		$json = json_decode($response->getContent(), TRUE);
		$this->assertNotEmpty($json);
		$this->assertInternalType('array', $json);
		$this->assertArrayHasKey('title', $json[0]);
		$this->assertEquals($this->task->getTitle(), $json[0]['title']);
	}

	/**
	 * test
	 */
	public function isCreateActionAppliedToHistoryService() {

		$this->fixture->createAction($this->meetingMock, $this->task);
		$this->persistenceManager->persistAll();

		$this->assertNotNull($this->historyRepository->findAll()->getFirst());
	}

}