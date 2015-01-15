<?php
namespace OpenAgenda\Application\Tests\Unit\Service\Fixture;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group small
 */
class EntityWithModificationInterface implements \OpenAgenda\Application\Domain\Model\ModificationInterface {

	/**
	 * @var \DateTime
	 */
	protected $modificationDate;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithModificationInterface>
	 */
	protected $collection;

	/**
	 * Initializes this object.
	 */
	public function __construct() {
		$this->collection = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param \DateTime $modificationDate
	 * @return void
	 */
	public function setModificationDate(\DateTime $modificationDate) {
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
	 */
	public function getCollection() {
		return $this->collection;
	}

}
