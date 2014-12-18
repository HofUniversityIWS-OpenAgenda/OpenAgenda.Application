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
 * @ORM\Table(name="oa_note")
 */
class Note implements CreationInterface {

	/**
	 * @var \OpenAgenda\Application\Domain\Model\AgendaItem
	 * @ORM\OneToOne(mappedBy="note")
	 */
	protected $agendaItem;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $description;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $creationDate;

	/**
	 * @return AgendaItem
	 */
	public function getAgendaItem() {
		return $this->agendaItem;
	}

	/**
	 * @param AgendaItem $agendaItem
	 */
	public function setAgendaItem(AgendaItem $agendaItem) {
		$this->agendaItem = $agendaItem;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate(\DateTime $creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}


}