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
class PersonRepository extends \TYPO3\Party\Domain\Repository\PartyRepository {

	const ENTITY_CLASSNAME = 'OpenAgenda\\Application\\Domain\\Model\\Person';

	/**
	 * @param array|\TYPO3\Party\Domain\Model\ElectronicAddress[] $electronicAddresses
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Person[]
	 */
	public function findByElectronicAddresses(array $electronicAddresses) {
		$constraints = array();
		$query = $this->createQuery();

		foreach ($electronicAddresses as $electronicAddress) {
			$constraints[] = $query->contains('electronicAddresses', $electronicAddress);
		}

		$query->matching($query->logicalOr($constraints));
		return $query->execute();
	}

}