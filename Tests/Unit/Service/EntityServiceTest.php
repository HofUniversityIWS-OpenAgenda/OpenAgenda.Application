<?php
namespace OpenAgenda\Application\Tests\Unit\Service;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Tests invocation of EntityService.
 *
 * @group small
 * @author Oliver Hader <oliver@typo3.org>
 */
class EntityServiceTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = FALSE;

	/**
	 * @var \OpenAgenda\Application\Service\EntityService|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * @var \TYPO3\Flow\Persistence\Generic\PersistenceManager|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $persistenceManagerMock;

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $reflectionServiceMock;

	/**
	 * @var array
	 */
	protected $reflectionClassPropertyNames = array();

	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->persistenceManagerMock = $this->getMock(
			'TYPO3\\Flow\\Persistence\\Generic\\PersistenceManager',
			array('isNewObject'), array(), '', FALSE
		);

		$this->reflectionServiceMock = $this->getMock(
			'TYPO3\\Flow\\Reflection\\ReflectionService',
			array('getClassPropertyNames'), array(), '', FALSE
		);
		$this->reflectionServiceMock->expects($this->any())
			->method('getClassPropertyNames')
			->will(
				$this->returnCallback(
					array($this, 'reflectionServiceGetClassPropertyNamesCallback')
				)
			);

		$this->persistenceManagerMock = $this->getMock(
			'TYPO3\\Flow\\Persistence\\Generic\\PersistenceManager',
			array('isNewObject'), array(), '', FALSE
		);

		$this->fixture = $this->getAccessibleMock(
			'OpenAgenda\\Application\\Service\\EntityService',
			array('_none')
		);
		$this->fixture->_set('persistenceManager', $this->persistenceManagerMock);
		$this->fixture->_set('reflectionService', $this->reflectionServiceMock);
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->reflectionClassPropertyNames);
		unset($this->persitenceManagerMock);
		unset($this->reflectionServiceMock);
		unset($this->fixture);
	}

	/**
	 * @return array
	 */
	public function reflectionServiceGetClassPropertyNamesCallback() {
		return $this->reflectionClassPropertyNames;
	}

	/**
	 * @test
	 */
	public function isCreationDateAppliedToNewEntity() {
		$this->persistenceManagerMock->expects($this->once())->method('isNewObject')->will($this->returnValue(TRUE));

		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithCreationInterface();
		$this->fixture->applyStatusDates($entity);
		$this->assertInstanceOf('DateTime', $entity->getCreationDate());
	}

	/**
	 * @test
	 */
	public function isCreationDateNotAppliedToExistingEntity() {
		$this->persistenceManagerMock->expects($this->once())->method('isNewObject')->will($this->returnValue(FALSE));

		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithCreationInterface();
		$this->fixture->applyStatusDates($entity);
		$this->assertNull($entity->getCreationDate());
	}

	/**
	 * @test
	 */
	public function isModificationDateAppliedToEntity() {
		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithModificationInterface();
		$this->fixture->applyStatusDates($entity);
		$this->assertInstanceOf('DateTime', $entity->getModificationDate());
	}

	/**
	 * @test
	 */
	public function isNeitherCreationDateNorModificationDateAppliedToEntity() {
		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithoutCreationOrModificationInterface();
		$this->fixture->applyStatusDates($entity);
		$this->assertNull($entity->getCreationDate());
		$this->assertNull($entity->getModificationDate());
	}

	/**
	 * @test
	 */
	public function areCreationDatesAppliedToSubEntities() {
		$this->persistenceManagerMock->expects($this->exactly(2))->method('isNewObject')->will($this->returnValue(TRUE));
		$this->reflectionClassPropertyNames = array('collection');

		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithCreationInterface();
		$subEntity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithCreationInterface();
		$entity->getCollection()->add($subEntity);

		$this->fixture->applyStatusDates($entity, TRUE);
		$this->assertInstanceOf('DateTime', $entity->getCreationDate());
		$this->assertInstanceOf('DateTime', $subEntity->getCreationDate());
	}

	/**
	 * @test
	 */
	public function areModificationDatesAppliedToSubEntities() {
		$this->reflectionClassPropertyNames = array('collection');

		$entity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithModificationInterface();
		$subEntity = new \OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithModificationInterface();
		$entity->getCollection()->add($subEntity);

		$this->fixture->applyStatusDates($entity, TRUE);
		$this->assertInstanceOf('DateTime', $entity->getModificationDate());
		$this->assertInstanceOf('DateTime', $subEntity->getModificationDate());
	}

}
