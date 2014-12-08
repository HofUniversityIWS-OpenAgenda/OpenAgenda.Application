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
 * @ORM\Table(name="oa_task")
 */
class Task extends ProtocolItem implements ModificationInterface {

	/**
	 * @var \TYPO3\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 * @OA\ToFlatArray(useIdentifier=true)
	 */
	protected $assignee;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $title;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $dueDate;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $description;

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
	 * @return \TYPO3\Party\Domain\Model\Person
	 */
	public function getAssignee() {
		return $this->assignee;
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
	public function getDueDate() {
		return $this->dueDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
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
	 * @param \TYPO3\Party\Domain\Model\Person
	 */
	public function setAssignee(\TYPO3\Party\Domain\Model\Person $assignee) {
		$this->assignee = $assignee;
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
	 * @param \DateTime $dueDate
	 */
	public function setDueDate($dueDate) {
		$this->dueDate = $dueDate;
	}

	/**
	 * @param \DateTime $modificationDate
	 */
	public function setModificationDate(\DateTime $modificationDate) {
		$this->modificationDate = $modificationDate;
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