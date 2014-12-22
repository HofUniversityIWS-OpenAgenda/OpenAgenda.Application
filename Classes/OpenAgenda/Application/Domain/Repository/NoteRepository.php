<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;
use TYPO3\Flow\Persistence\Repository;

/**
 * Class NoteRepository
 *
 * @package OpenAgenda\Application\Domain\Repository
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 * @Flow\Scope("singleton")
 */
class NoteRepository extends Repository {

	/**
 	* @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	* @return object The matching object if found, otherwise NULL
	*/
	public function findByMeeting(Meeting $meeting) {
		$query = $this->createQuery();
		$query->matching($query->equals('meeting', $meeting));
		return $query->execute();
	}

}