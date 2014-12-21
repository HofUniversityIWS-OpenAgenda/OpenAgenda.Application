<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Person;

/**
 * Class InvitationRepository
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Domain\Repository
 * @author Oliver Hader <oliver@typo3.org>
 */
class InvitationRepository extends AbstractRepository {

	/**
	 * @param Person $person
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Invitation[]
	 */
	public function findByPerson(Person $person = NULL) {
		if ($person === NULL) {
			$person = $this->getPerson();
		}

		$query = $this->createQuery();
		$query->matching($query->equals('participant', $person));
		return $query->execute();
	}

}