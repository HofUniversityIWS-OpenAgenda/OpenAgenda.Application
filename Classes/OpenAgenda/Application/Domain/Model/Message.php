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
	 * @var \string
	 */
	protected $subject;

	/**
	 * @var \string
	 */
	protected $richTextBody;

	/**
	 * @var \string
	 */
	protected $plainTextBody;

}