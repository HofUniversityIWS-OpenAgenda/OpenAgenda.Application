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
class Meeting {

    /**
     * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Domain\Model\AgendaItem>
     * @ORM\OneToMany(mappedBy="meeting")
     */
    protected $agendaItems;

    /**
     * @var \Doctrine\Common\Collections\Collection<\OpenAgenda\Application\Domain\Model\ProtocolItem>
     * @ORM\OneToMany(mappedBy="meeting")
     */
    protected $protocolItems;

	/**
	 * @var string
	 */
	protected $title;

    /**
	 * @var \DateTime
	 */
	protected $startDate;

    /**
	 * @var \DateTime
	 */
	protected $endDate;

    /**
	 * @var integer
	 */
	protected $status;

    /**
	 * @var \DateTime
	 */
	protected $creationDate;

    /**
	 * @var \DateTime
	 */
	protected $modificationDate;


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return \DateTime
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgendaItems()
    {
        return $this->agendaItems;
    }

    /**
     * @return mixed
     */
    public function getProtocolItems()
    {
        return $this->protocolItems;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

}