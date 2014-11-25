<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;

/**
 * @Flow\Scope("singleton")
 */
class TaskRepository extends AbstractRepository {

	/**
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Task[]
	 * @todo SECURITY: Only allowed entities shall be queried
	 */
	public function findAllowed() {
		return $this->findAll();
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByMeeting(Meeting $meeting) {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}

}