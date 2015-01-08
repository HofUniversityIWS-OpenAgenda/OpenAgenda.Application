<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Person;
use OpenAgenda\Application\Domain\Model\Meeting;
use OpenAgenda\Application\Domain\Model\Invitation;

/**
 * Class InvitationRepository
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Domain\Repository
 * @author Oliver Hader <oliver@typo3.org>
 */
class InvitationRepository extends AbstractRepository {

	/**
	 * Finds invitation entities for one Person entity.
	 *
	 * @param Person $person The Person entity to be searched for
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

	/**
	 * Finds invitation entities of a Meeting entity.
	 *
	 * @param Meeting $meeting The Meeting entity to be used
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|Invitation[]
	 */
	public function findByMeeting(Meeting $meeting) {
		$query = $this->createQuery();
		$query->matching($query->equals('meeting', $meeting));
		return $query->execute();
	}

	/**
	 * Finds all open invitation entities for a Person entity.
	 *
	 * @param Person $person The Person entity to be searched for
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Invitation[]
	 */
	public function findOpen(Person $person = NULL) {
		if ($person === NULL) {
			$person = $this->getPerson();
		}

		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('participant', $person),
				$query->equals('status', 0)
			)
		);
		return $query->execute();
	}

}