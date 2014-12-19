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
 * @ORM\Table(name="oa_message")
 */
class Message {

	const STATUS_Created = 0;
	const STATUS_Active = 2;
	const STATUS_Failure = 5;
	const STATUS_Delivered = 9;

	/**
	 * @return Message
	 */
	static public function create() {
		$message = new Message();
		$message->setDateTime(new \DateTime());
		$message->setStatus(static::STATUS_Created);
		return $message;
	}

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Flow\Resource\Resource>
	 * @ORM\ManyToMany
	 * @ORM\JoinTable(name="oa_message_attachments")
	 * @OA\ToFlatArray(scope="show")
	 */
	protected $attachments;

	/**
	 * @var \TYPO3\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 * @OA\ToFlatArray(callback="OpenAgenda\Application\Service\ArrayService->prepare($self)")
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
	 * @ORM\Column(type="text")
	 */
	protected $richTextBody;

	/**
	 * @var string
	 * @OA\ToFlatArray
	 * @ORM\Column(type="text")
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

	/**
	 * @return bool
	 */
	public function isActive() {
		return ($this->status === static::STATUS_Active);
	}

}