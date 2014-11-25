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
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findAllowed() {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}
	/**
	 * @return object The matching object if found, otherwise NULL
	 */
	public function findByFilterConstraint() {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}

}