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
class Invitation {

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\ManyToOne(inversedBy="accountIdentifier")
	 * @OA\ToArray
	 */
	protected $participant;

	/**
	 * @var integer
	 * @OA\ToArray
	 */
	protected $status;

	/**
	 * @var \DateTime
	 * @OA\ToArray(callback="$self->format('c')")
	 */
	protected $creationDate;

	/**
	 * @var \DateTime
	 * @OA\ToArray(callback="$self->format('c')")
	 */
	protected $modificationDate;

	/**
	 * @return \DateTime
	 */
	public function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate()
	{
		return $this->modificationDate;
	}

	/**
	 * @return mixed
	 */
	public function getParticipant()
	{
		return $this->participant;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate)
	{
		$this->creationDate = $creationDate;
	}

	/**
	 * @param \DateTime $modificationDate
	 */
	public function setModificationDate($modificationDate)
	{
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @param mixed $participant
	 */
	public function setParticipant($participant)
	{
		$this->participant = $participant;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}


}