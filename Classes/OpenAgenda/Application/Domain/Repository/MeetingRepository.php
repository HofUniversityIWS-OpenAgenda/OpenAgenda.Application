<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class MeetingRepository extends AbstractRepository {

	/**
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Meeting[]
	 * @todo SECURITY: Only allowed entities shall be queried
	 */
	public function findAllowed() {
		return $this->findAll();
	}

	/**
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByFilterConstraint() {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}

}