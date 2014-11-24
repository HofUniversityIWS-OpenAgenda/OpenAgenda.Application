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
 * @Flow\Scope("singleton")
 */
class NoteRepository extends Repository {

	/**
 	* @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	* @return object The matching object if found, otherwise NULL
	*/
	public function findByMeeting(Meeting $meeting) {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}

}