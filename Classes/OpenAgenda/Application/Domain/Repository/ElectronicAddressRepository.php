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
class ElectronicAddressRepository extends \TYPO3\Party\Domain\Repository\PartyRepository {

	const ENTITY_CLASSNAME = 'TYPO3\Party\Domain\Model\ElectronicAddress';

	/**
	 * @param array $mailAddresses
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\TYPO3\Party\Domain\Model\ElectronicAddress[]
	 */
	public function findByMailAddresses(array $mailAddresses) {
		$constraints = array();
		$query = $this->createQuery();

		foreach ($mailAddresses as $mailAddress) {
			$constraints[] = $query->equals('identifier', $mailAddress);
		}

		$query->matching($query->logicalOr($constraints));
		return $query->execute();
	}

}