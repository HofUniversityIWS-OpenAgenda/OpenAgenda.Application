<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class AbstractRepository extends Repository {

	/**
	 * @var
	 */
	protected $filterConstraintService;

	/**
	* @return object The matching object if found, otherwise NULL
	*/
	public function findByFilterConstraint() {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}

	/**
	* @return object The matching object if found, otherwise NULL
	*/
	public function findByAccount() {
		//return $this->persistenceManager->getObjectByIdentifier($identifier, $this->entityClassName);
	}

}