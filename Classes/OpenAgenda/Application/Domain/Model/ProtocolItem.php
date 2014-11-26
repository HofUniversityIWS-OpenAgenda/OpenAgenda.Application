<?php
namespace OpenAgenda\Application\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use OpenAgenda\Application\Framework\Annotations as OA;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class ProtocolItem {

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting
	 * @ORM\ManyToOne(inversedBy="protocolItems")
	 * @OA\ToFlatArray(useIdentifier=true)
	 */
	protected $meeting;

	/**
	 * @var integer
	 * @OA\ToFlatArray
	 */
	protected $sorting;

	/**
	 * @return Meeting
	 */
	public function getMeeting() {
		return $this->meeting;
	}

	/**
	 * @return int
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * @param Meeting $meeting
	 */
	public function setMeeting($meeting) {
		$this->meeting = $meeting;
	}

	/**
	 * @param int $sorting
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

}