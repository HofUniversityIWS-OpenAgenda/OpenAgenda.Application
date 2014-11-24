<?php
namespace OpenAgenda\Application\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Message {

	/**
	 * @var \Doctrine\Common\Collections\Collection<\TYPO3\Flow\Resource\Resource>
	 * @ORM\ManyToMany
	 */
	protected $attachments;

	/**
	 * @var \TYPO3\Flow\Security\Account
	 * @ORM\OneToOne(mappedBy="accountIdentifier")
	 */
	protected $recipient;

	/**
	 * @var string
	 */
	protected $subject;

	/**
	 * @var string
	 */
	protected $richTextBody;

	/**
	 * @var string
	 */
	protected $plainTextBody;

	/**
	 * @var integer
	 */
	protected $status;

	/**
	 * @var \DateTime
	 */
	protected $DateTime;

	/**
	 * @return \DateTime
	 */
	public function getDateTime()
	{
		return $this->DateTime;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getAttachments()
	{
		return $this->attachments;
	}

	/**
	 * @return string
	 */
	public function getPlainTextBody()
	{
		return $this->plainTextBody;
	}

	/**
	 * @return \TYPO3\Flow\Security\Account
	 */
	public function getRecipient()
	{
		return $this->recipient;
	}

	/**
	 * @return string
	 */
	public function getRichTextBody()
	{
		return $this->richTextBody;
	}

	/**
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * @param \DateTime $DateTime
	 */
	public function setDateTime($DateTime)
	{
		$this->DateTime = $DateTime;
	}

	/**
	 * @param \Doctrine\Common\Collections\Collection $attachments
	 */
	public function setAttachments($attachments)
	{
		$this->attachments = $attachments;
	}

	/**
	 * @param string $plainTextBody
	 */
	public function setPlainTextBody($plainTextBody)
	{
		$this->plainTextBody = $plainTextBody;
	}

	/**
	 * @param \TYPO3\Flow\Security\Account $recipient
	 */
	public function setRecipient($recipient)
	{
		$this->recipient = $recipient;
	}

	/**
	 * @param string $richTextBody
	 */
	public function setRichTextBody($richTextBody)
	{
		$this->richTextBody = $richTextBody;
	}

	/**
	 * @param int $status
	 */
	public function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @param string $subject
	 */
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

}