<?php
namespace OpenAgenda\Application\Tests\Functional\Controller\Fixture;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Framework\Annotations as OA;

/**
 * @group small
 */
class SimpleMeetingEntity {

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

}
