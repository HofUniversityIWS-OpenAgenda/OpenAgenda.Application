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
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meetingRepositoryMock;

	/**
	 * @var \OpenAgenda\Application\Service\Security\PermissionService |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $permissionServiceMock;

	/**
	 * @var string
	 */
	protected $meetingIdentifier;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting |\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $meeting;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->permissionServiceMock = $this->getMock(
			'OpenAgenda\Application\\Service\\Security\\PermissionService',
			array('hasManagingRole', 'hasMinuteTakerRole'), array()
		);

		$this->objectManager->setInstance(
			'OpenAgenda\\Application\\Service\\Security\\PermissionService',
			$this->permissionServiceMock
		);

		$this->meetingRepositoryMock = $this->getMock(
			'OpenAgenda\\Application\\Domain\\Repository\\MeetingRepository',
			array('findAllowed'), array()
		);

		$this->objectManager->setInstance(
			'OpenAgenda\\Application\\Domain\\Repository\\MeetingRepository',
			$this->meetingRepositoryMock
		);

		$this->fixture = new \OpenAgenda\Application\Controller\MeetingController();

		$this->meeting = new \OpenAgenda\Application\Domain\Model\Meeting();
		$this->meeting->setCreationDate(new \DateTime());
		$this->meeting->setModificationDate($this->meeting->getCreationDate());
		$this->meeting->setScheduledStartDate(new \DateTime('2015-01-05 12:00'));
		$this->meeting->setStatus(\OpenAgenda\Application\Domain\Model\Meeting::STATUS_CREATED);
		$this->meeting->setTitle(uniqid('Title'));

		$this->meetingIdentifier = \TYPO3\Flow\Reflection\ObjectAccess::getProperty(
			$this->meeting,
			'Persistence_Object_Identifier',
			TRUE
		);
	}

	/**
	* Tears down this test case.
	*/
	public function tearDown() {
		unset($this->fixture);
		unset($this->meeting);
		unset($this->meetingIdentifier);
		unset($this->meetingRepositoryMock);
		unset($this->permissionServiceMock);
	}


	/**
	 * @test
	 */
	public function listActionReturnsMeetingJsonInBrowser() {
		$queryResult = array($this->meeting);

		$this->meetingRepositoryMock
			->expects($this->once())
			->method('findAllowed')
			->will($this->returnValue($queryResult));

		$response = $this->browser->request('http://flow.localhost/meeting/list.json');

		$this->assertEquals(200, $response->getStatusCode());

		$json = json_decode($response->getContent(), TRUE);
		$this->assertNotEmpty($json);
		$this->assertInternalType('array', $json);
		$this->assertArrayHasKey('title', $json[0]);
		$this->assertEquals($this->meeting->getTitle(), $json[0]['title']);
	}

	/**
	 * @test
	 */
	public function showActionReturnsMeetingJsonInBrowser() {
		$response = $this->browser->request('http://flow.localhost/meeting/' . $this->meetingIdentifier . '/show.json');

		$this->assertEquals(200, $response->getStatusCode());

		$json = json_decode($response->getContent(), TRUE);
		$this->assertNotEmpty($json);
		$this->assertInternalType('array', $json);
		$this->assertArrayHasKey('title', $json);
		$this->assertEquals($this->meeting->getTitle(), $json['title']);
	}

}
