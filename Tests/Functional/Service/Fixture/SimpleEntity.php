<?php
namespace OpenAgenda\Application\Tests\Functional\Service\Fixture;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Framework\Annotations as OA;

/**
 * @group small
 */
class SimpleEntity {

	/**
	 * @var string
	 */
	protected $incognito = 'incognito';

	/**
	 * @var string
	 * @OA\ToFlatArray(scope="list,collection,never")
	 */
	protected $title;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(scope="show,!never",callback="$self->format('c')")
	 */
	protected $date;

	/**
	 * @var \Doctrine\Common\Collections\Collection<OpenAgenda\Application\Tests\Functional\Service\Fixture\SimpleEntity>
	 * @OA\ToFlatArray(scope="collection,!never")
	 */
	protected $collection;

	/**
	 * Initializes this object.
	 */
	public function __construct() {
		$this->collection = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return string
	 */
	public function getIncognito() {
		return $this->incognito;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDate(\DateTime $date) {
		$this->date = $date;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\Common\Collections\Collection
	 */
	public function getCollection() {
		return $this->collection;
	}

}
