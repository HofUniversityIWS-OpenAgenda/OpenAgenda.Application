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
class Message {

	/**
	 * @return Message
	 */
	static public function create() {
		/** @var Message $message */
		$message = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get(__CLASS__);
		$message->setDateTime(new \DateTime());
		$message->setStatus(0);
		return $message;
	}

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Flow\Resource\Resource>
	 * @ORM\ManyToMany
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $attachments;

	/**
	 * @var \TYPO3\Party\Domain\Model\Person
	 * @ORM\OneToOne
	 * @OA\ToFlatArray
	 */
	protected $recipient;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $subject;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $richTextBody;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 */
	protected $plainTextBody;

	/**
	 * @var integer
	 * @OA\ToFlatArray
	 */
	protected $status;

	/**
	 * @var \DateTime
	 * @OA\ToFlatArray(callback="$self->format('c')")
	 */
	protected $dateTime;

	/**
	 * @return \DateTime
	 */
	public function getDateTime() {
		return $this->dateTime;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * @return string
	 */
	public function getPlainTextBody() {
		return $this->plainTextBody;
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\Person
	 */
	public function getRecipient() {
		return $this->recipient;
	}

	/**
	 * @return string
	 */
	public function getRichTextBody() {
		return $this->richTextBody;
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
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * @param \DateTime $dateTime
	 */
	public function setDateTime(\DateTime $dateTime) {
		$this->dateTime = $dateTime;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $attachments
	 */
	public function setAttachments(\Doctrine\Common\Collections\Collection $attachments) {
		$this->attachments = $attachments;
	}

	/**
	 * @param string $plainTextBody
	 */
	public function setPlainTextBody($plainTextBody) {
		$this->plainTextBody = $plainTextBody;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\Person $recipient
	 */
	public function setRecipient(\TYPO3\Party\Domain\Model\Person $recipient) {
		$this->recipient = $recipient;
	}

	/**
	 * @param string $richTextBody
	 */
	public function setRichTextBody($richTextBody) {
		$this->richTextBody = $richTextBody;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

}