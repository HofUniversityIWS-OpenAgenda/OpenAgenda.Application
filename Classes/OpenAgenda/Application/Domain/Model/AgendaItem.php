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
class AgendaItem implements ModificationInterface {

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
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Flow\Resource\Resource>
	 * @ORM\ManyToMany
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $resources;

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
	 * @param \DateTime $creationDate
	 */
	public function setCreationDate($creationDate) {
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

}