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
 * @ORM\Table(name="oa_meeting")
 */
class Meeting implements CreationInterface, ModificationInterface {

	const STATUS_CREATED = 0;
	const STATUS_COMMITTED = 1;
	const STATUS_STARTED = 2;
	const STATUS_CLOSED = 3;
	const STATUS_CANCELED = 4;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Domain\Model\AgendaItem>
	 * @ORM\OneToMany(mappedBy="meeting",cascade="persist")
	 * @ORM\OrderBy({"sorting" = "ASC"})
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
	 * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Domain\Model\Invitation>
	 * @ORM\OneToMany(mappedBy="meeting",cascade="persist")
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $invitations;

	/**
	 * @var array
	 * @Flow\Transient
	 * @OA\ToFlatArray(callback="$self->determineInvitationStatus()")
	 */
	protected $invitationStatus;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $title;

	/**
	 * @var string
	 * @ORM\Column(nullable=true)
	 * @OA\ToFlatArray
	 */
	protected $location;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $scheduledStartDate;

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
		$this->invitations = new \Doctrine\Common\Collections\ArrayCollection();
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
	public function getScheduledStartDate() {
		return $this->scheduledStartDate;
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
	 * @return \Doctrine\Common\Collections\Collection|Invitation[]
	 */
	public function getInvitations() {
		return $this->invitations;
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
	 * @return string
	 */
	public function getLocation() {
		return $this->location;
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
	public function setCreationDate(\DateTime $creationDate) {
		$this->creationDate = $creationDate;
	}

	/**
	 * @param \DateTime $scheduledStartDate
	 */
	public function setScheduledStartDate(\DateTime $scheduledStartDate) {
		$this->scheduledStartDate = $scheduledStartDate;
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
	public function setModificationDate(\DateTime $modificationDate) {
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $protocolItems
	 */
	public function setProtocolItems($protocolItems) {
		$this->protocolItems = $protocolItems;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $invitations
	 */
	public function setInvitations($invitations) {
		$this->invitations = $invitations;
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

	/**
	 * @param string $location
	 */
	public function setLocation($location) {
		$this->title = $location;
	}

	/**
	 * @return array
	 */
	public function determineInvitationStatus() {
		$invitationsStatus = array(
			'open' => 0,
			'committed' => 0,
			'canceled' => 0,
		);

		foreach ($this->getInvitations() as $invitation){
			if($invitation->getStatus() === 0) {
				$invitationsStatus['open']++;
			}  else if($invitation->getStatus() === 1){
				$invitationsStatus['committed']++;
			} else if($invitation->getStatus() === 2){
				$invitationsStatus['canceled']++;
			}
		}

		return $invitationsStatus;
	}

}