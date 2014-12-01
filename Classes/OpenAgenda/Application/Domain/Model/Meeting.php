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
class Meeting {

	const STATUS_CREATED = 0;
	const STATUS_COMMIT = 1;
	const STATUS_STARTED = 2;
	const STATUS_CLOSED = 3;
	const STATUS_CANCEL = 4;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Domain\Model\AgendaItem>
	 * @ORM\OneToMany(mappedBy="meeting",cascade="persist")
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $agendaItems;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Domain\Model\ProtocolItem>
	 * @ORM\OneToMany(mappedBy="meeting",cascade="persist")
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $protocolItems;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $title;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $scheduleStartDate;

	/**
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $startDate;

	/**
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $endDate;

	/**
	 * @var integer
	 * @OA\ToFlatArray
	 */
	protected $status;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $creationDate;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $modificationDate;

	/**
	 * Initializes this object.
	 */
	public function __construct() {
		$this->agendaItems = new \Doctrine\Common\Collections\ArrayCollection();
		$this->protocolItems = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getAgendaItems() {
		return $this->agendaItems;
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
	public function getScheduleStartDate() {
		return $this->$scheduleStartDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getProtocolItems() {
		return $this->protocolItems;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $agendaItems
	 */
	public function setAgendaItems($agendaItems) {
		$this->agendaItems = $agendaItems;
	}

	/**
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @param \DateTime $scheduleStartDate
	 */
	public function setScheduleStartDate($scheduleStartDate) {
		$this->scheduleStartDate = $scheduleStartDate;
	}

	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	/**
	 * @param \DateTime $modificationDate
	 */
	public function setModificationDate($modificationDate) {
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $protocolItems
	 */
	public function setProtocolItems($protocolItems) {
		$this->protocolItems = $protocolItems;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

}