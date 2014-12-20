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
 * @ORM\Table(name="oa_invitation", uniqueConstraints={@ORM\UniqueConstraint(columns={"meeting", "participant"})})
 * @OA\ToFlatArray(scope="show",transientName="$participant",callback="OpenAgenda\Application\Service\ArrayService->prepare($participant)")
 */
class Invitation implements CreationInterface, ModificationInterface {

	const STATUS_OPEN = 0;
	const STATUS_COMMITTED = 1;
	const STATUS_CANCELED = 2;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting
	 * @ORM\ManyToOne(inversedBy="invitations")
	 */
	protected $meeting;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Person
	 * @ORM\ManyToOne
	 * @OA\ToFlatArray(useIdentifier=true)
	 */
	protected $participant;

	/**
	 * @var integer
	 * @OA\ToFlatArray
	 */
	protected $status = 0;

	/**
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * @var \DateTime
	 */
	protected $modificationDate;

	/**
	 * @return Meeting
	 */
	public function getMeeting() {
		return $this->meeting;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
	}

	/**
	 * @return \OpenAgenda\Application\Domain\Model\Person
	 */
	public function getParticipant() {
		return $this->participant;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @param Meeting $meeting
	 */
	public function setMeeting($meeting) {
		$this->meeting = $meeting;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate(\DateTime $creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @param \DateTime $modificationDate
	 */
	public function setModificationDate(\DateTime $modificationDate) {
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Person $participant
	 */
	public function setParticipant(\OpenAgenda\Application\Domain\Model\Person $participant) {
		$this->participant = $participant;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}


}