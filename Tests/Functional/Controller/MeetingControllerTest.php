<?php
namespace OpenAgenda\Application\Tests\Functional\Controller;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group small
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class MeetingControllerTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	/**
	 * @var \OpenAgenda\Application\Controller\MeetingController |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meetingRepository;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meeting;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();


		$this->registerRoute('meetingcontrollerListAction', 'test/meetingcontroller(/{@action})', array(
			'@package' => 'OpenAgenda.Application',
			'@subpackage' => 'Tests\Functional\Controller\Fixture',
			'@controller' => 'MeetingController',
			'@action' => 'list',
			'@format' =>'json'
		));

		$this->fixture = new \OpenAgenda\Application\Controller\MeetingController();
		$this->meetingRepository = new \OpenAgenda\Application\Domain\Repository\MeetingRepository();
		$this->meeting =  new \OpenAgenda\Application\Domain\Model\Meeting();

		$this->meeting->setCreationDate(new \DateTime());
		$this->meeting->setModificationDate($this->meeting->getCreationDate());
		$this->meeting->setScheduledStartDate(new \DateTime('2015-01-05 12:00'));
		$this->meeting->setStatus(\OpenAgenda\Application\Domain\Model\Meeting::STATUS_CREATED);
		$this->meeting->setTitle('Meetingtitle');

		$this->meetingRepository->add($this->meeting);
		$this->persistenceManager->persistAll();

	}

	/**
	* Tears down this test case.
	*/
	public function tearDown() {
		unset($this->fixture);
//		unset($this->meetingRepository);
	}


	/**
	 * @test
	 */
	public function listActionReturnsMeetingJsonInBrowser() {
		$response = $this->browser->request('http://flow.localhost/test/meetingcontroller');
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertNotEquals('[]', $response->getContent());
	}

	/**
	 * @test
	 */
	public function showActionReturnsMeetingJsonInBrowser(){

		$response = $this->browser->request('http://flow.localhost/meeting/'.$this->meetingRepository->findByIdentifier($this->persistenceManager->getIdentifierByObject($this->meeting)).'/show.json');
		$this->assertEquals(200, $response->getStatusCode());
	}



}
