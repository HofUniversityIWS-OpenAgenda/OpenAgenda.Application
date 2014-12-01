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
class History {

	/**
	 * @var \TYPO3\Party\Domain\Model\Person
	 * @ORM\OneToOne
	 * @OA\ToFlatArray
	 */
	protected $issuer;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $entityIdentifier;

	/**
	 * @var \string
	 * @OA\ToFlatArray
	 */
	protected $entityType;

	/**
	 * @var \string
	 * @OA\ToFlatArray
	 */
	protected $previousData;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $creationDate;

	/**
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @return string
	 */
	public function getEntityIdentifier() {
		return $this->entityIdentifier;
	}

	/**
	 * @return string
	 */
	public function getEntityType() {
		return $this->entityType;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\Person
	 */
	public function getIssuer() {
		return $this->issuer;
	}

	/**
	 * @return string
	 */
	public function getPreviousData() {
		return $this->previousData;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @param string $entityIdentifier
	 */
	public function setEntityIdentifier($entityIdentifier) {
		$this->entityIdentifier = $entityIdentifier;
	}

	/**
	 * @param string $entityType
	 */
	public function setEntityType($entityType) {
		$this->entityType = $entityType;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\Person $issuer
	 */
	public function setIssuer(\TYPO3\Party\Domain\Model\Person $issuer) {
		$this->issuer = $issuer;
	}

	/**
	 * @param string $previousData
	 */
	public function setPreviousData($previousData) {
		$this->previousData = $previousData;
	}

}