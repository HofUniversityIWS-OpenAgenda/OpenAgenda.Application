<?php
namespace OpenAgenda\Application\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use OpenAgenda\Application\Framework\Annotations as OA;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model Preference
 *
 * This entity is currently not used.
 *
 * @Flow\Entity
 * @ORM\Table(name="oa_preference")
 * @author Oliver Hader <oliver@typo3.org>
 */
class Preference implements ModificationInterface {

	const NOTIFICATION_TypeImmediately = 0;
	const NOTIFICATION_TypeDaily = 1;
	const NOTIFICATION_TypeWeekly = 2;

	/**
	 * @var \DateTime
	 */
	protected $modificationDate;

	/**
	 * @var int
	 * @OA\ToFlatArray
	 */
	protected $notificationOnAgendaChange = self::NOTIFICATION_TypeImmediately;

	/**
	 * @var int
	 * @OA\ToFlatArray
	 */
	protected $notificationOnScheduleChange = self::NOTIFICATION_TypeImmediately;

	/**
	 * @var int
	 * @OA\ToFlatArray
	 */
	protected $notificationOnDescriptionChange = self::NOTIFICATION_TypeImmediately;

	/**
	 * @return int
	 */
	public function getNotificationOnAgendaChange() {
		return $this->notificationOnAgendaChange;
	}

	/**
	 * @param int $notificationOnAgendaChange
	 */
	public function setNotificationOnAgendaChange($notificationOnAgendaChange) {
		$this->notificationOnAgendaChange = $notificationOnAgendaChange;
	}

	/**
	 * @return int
	 */
	public function getNotificationOnScheduleChange() {
		return $this->notificationOnScheduleChange;
	}

	/**
	 * @param int $notificationOnScheduleChange
	 */
	public function setNotificationOnScheduleChange($notificationOnScheduleChange) {
		$this->notificationOnScheduleChange = $notificationOnScheduleChange;
	}

	/**
	 * @return int
	 */
	public function getNotificationOnDescriptionChange() {
		return $this->notificationOnDescriptionChange;
	}

	/**
	 * @param int $notificationOnDescriptionChange
	 */
	public function setNotificationOnDescriptionChange($notificationOnDescriptionChange) {
		$this->notificationOnDescriptionChange = $notificationOnDescriptionChange;
	}

	/**
	 * @return \DateTime
	 */
	public function getModificationDate() {
		return $this->modificationDate;
	}

	/**
	 * @param \DateTime $modificationDate
	 * @return void
	 */
	public function setModificationDate(\DateTime $modificationDate) {
		$this->modificationDate = $modificationDate;
	}

}