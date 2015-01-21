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
class SimpleInvitationModelWithStatus extends \OpenAgenda\Application\Domain\Model\Invitation {

	const STATUS_OPEN = 0;
	const STATUS_COMMITTED = 1;
	const STATUS_CANCELED = 2;

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
