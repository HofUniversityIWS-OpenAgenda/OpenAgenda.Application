<?php
namespace OpenAgenda\Application\Tests\Unit\Controller\Fixture;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group small
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class SimpleMeetingModelWithInitValue extends \OpenAgenda\Application\Domain\Model\Meeting {

	const STATUS_CREATED = 0;
	const STATUS_COMMITTED = 1;
	const STATUS_STARTED = 2;
	const STATUS_CLOSED = 3;
	const STATUS_CANCELED = 4;

	/**
	 * @var integer
	 */
	protected $status = 0;


	/**
	 * @param int $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return int
	 */
	public function getStatus() {
		return $this->status;
	}

}
