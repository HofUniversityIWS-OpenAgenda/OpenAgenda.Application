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
 * @ORM\Table(name="oa_agendaitem")
 */
class AgendaItem implements CreationInterface, ModificationInterface, SortableInterface {

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Meeting
	 * @ORM\ManyToOne(inversedBy="agendaItems")
	 */
	protected $meeting;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $title;

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
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $modificationDate;

	/**
	 * @var integer
	 * @OA\ToFlatArray
	 */
	protected $sorting;

	/**
	 * @var \OpenAgenda\Application\Domain\Model\Note
	 * @ORM\OneToOne(mappedBy="meeting",cascade="persist")
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $note;

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Flow\Resource\Resource>
	 * @ORM\ManyToMany
	 * @ORM\JoinTable(name="oa_agendaitem_resources")
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $resources;

	/**
	 * @var boolean
	 * @ORM\Column(options={"default":"0"})
	 * @OA\ToFlatArray
	 */
	protected $checked = FALSE;

	/**
	 * @param Meeting $meeting
	 */
	public function setMeeting(Meeting $meeting) {
		$this->meeting = $meeting;
	}

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
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
	}

	/**
	 * @return Note
	 */
	public function getNote() {
		return $this->note;
	}

	/**
	 * @param Note $note
	 */
	public function setNote(Note $note) {
		$this->note = $note;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getResources() {
		return $this->resources;
	}

	/**
	 * @return int
	 */
	public function getSorting() {
		return $this->sorting;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return boolean
	 */
	public function getChecked() {
		return $this->checked;
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

	/**
	 * @param \DateTime $modificationDate
	 */
	public function setModificationDate(\DateTime $modificationDate) {
		$this->modificationDate = $modificationDate;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $resources
	 */
	public function setResources($resources) {
		$this->resources = $resources;
	}

	/**
	 * @param int $sorting
	 */
	public function setSorting($sorting) {
		$this->sorting = $sorting;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param boolean $checked
	 */
	public function setChecked($checked) {
		$this->checked = $checked;
	}

}