<?php
namespace OpenAgenda\Application\Tests\Unit\Service\Fixture;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group small
 */
class EntityWithCreationInterface implements \OpenAgenda\Application\Domain\Model\CreationInterface {

	/**
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Tests\Unit\Service\Fixture\EntityWithCreationInterface>
	 */
	protected $collection;

	/**
	 * Initializes this object.
	 */
	public function __construct() {
		$this->collection = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param \DateTime $creationDate
	 * @return void
	 */
	public function setCreationDate(\DateTime $creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
	 */
	public function getCollection() {
		return $this->collection;
	}

}
