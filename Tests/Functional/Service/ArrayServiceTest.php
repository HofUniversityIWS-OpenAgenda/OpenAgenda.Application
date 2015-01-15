<?php
namespace OpenAgenda\Application\Tests\Functional\Service;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Tests invocation of EntityService.
 *
 * @group medium
 * @author Oliver Hader <oliver@typo3.org>
 */
class ArrayServiceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = FALSE;

	/**
	 * @var \OpenAgenda\Application\Service\ArrayService|\PHPUnit_Framework_MockObject_MockObject
	 */
	protected $fixture;

	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->fixture = new \OpenAgenda\Application\Service\ArrayService();
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function isSimpleArrayProcessed() {
		$subject = array(
			'first' => 'firstValue',
			'second' => 'secondValue',
			'array' => array(
				'third' => 'thirdValue',
			),
		);

		$result = $this->fixture->flatten($subject);

		$this->assertSame($subject, $result);
	}

	/**
	 * @test
	 */
	public function isSimpleEntityProcessed() {
		$subject = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity();
		$subject->setTitle(uniqid('Title'));
		$subject->setDate(new \DateTime());

		$result = $this->fixture->flatten($subject);

		$this->assertInternalType('array', $result);
		$this->assertArrayNotHasKey('incognito', $result);

		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('date', $result);
		$this->assertArrayHasKey('collection', $result);
		$this->assertSame($subject->getTitle(), $result['title']);
		$this->assertSame($subject->getDate()->format('c'), $result['date']);
	}

	/**
	 * @test
	 */
	public function isSimpleEntityShowScopeProcessed() {
		$subject = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity();
		$subject->setTitle(uniqid('Title'));
		$subject->setDate(new \DateTime());

		$result = $this->fixture->flatten($subject, 'show');

		$this->assertInternalType('array', $result);
		$this->assertArrayNotHasKey('incognito', $result);
		$this->assertArrayNotHasKey('title', $result);
		$this->assertArrayNotHasKey('collection', $result);

		$this->assertArrayHasKey('date', $result);
		$this->assertSame($subject->getDate()->format('c'), $result['date']);
	}

	/**
	 * @test
	 */
	public function isSimpleEntityNeverScopeProcessed() {
		$subject = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity();
		$subject->setTitle(uniqid('Title'));
		$subject->setDate(new \DateTime());

		$result = $this->fixture->flatten($subject, 'never');

		$this->assertInternalType('array', $result);
		$this->assertArrayNotHasKey('incognito', $result);
		$this->assertArrayNotHasKey('date', $result);
		$this->assertArrayNotHasKey('collection', $result);

		$this->assertArrayHasKey('title', $result);
		$this->assertSame($subject->getTitle(), $result['title']);
	}

	/**
	 * @test
	 */
	public function isEntityWithCallbackOnNullValueProcessed() {
		$subject = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity();
		$subject->setTitle(uniqid('Title'));

		$result = $this->fixture->flatten($subject);

		$this->assertInternalType('array', $result);
		$this->assertArrayNotHasKey('incognito', $result);

		$this->assertArrayHasKey('title', $result);
		$this->assertArrayHasKey('date', $result);
		$this->assertSame($subject->getTitle(), $result['title']);
		$this->assertSame(NULL, $result['date']);
	}

	/**
	 * @test
	 */
	public function areEntityCollectionsProcessed() {
		$subject = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity();
		$subject->setTitle(uniqid('Title'));
		$collectionSubject = new \OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity();
		$collectionSubject->setTitle(uniqid('Title'));
		$subject->getCollection()->add($collectionSubject);

		$result = $this->fixture->flatten($subject, 'collection');

		$this->assertInternalType('array', $result);
		$this->assertArrayNotHasKey('incognito', $result);
		$this->assertArrayNotHasKey('date', $result);
		$this->assertArrayHasKey('collection', $result);
		$this->assertArrayNotHasKey('incognito', $result['collection']);
		$this->assertArrayNotHasKey('date', $result['collection']);

		$this->assertSame($subject->getTitle(), $result['title']);
		$this->assertSame($collectionSubject->getTitle(), $result['collection'][0]['title']);
	}

}
