<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Person;

/**
 * Class MeetingRepository
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Domain\Repository
 * @author Oliver Hader <oliver@typo3.org>
 */
class MeetingRepository extends AbstractRepository {

	/**
	 * Finds allowed Meeting entities.
	 *
	 * If the $person argument is not submitted, the Person entity
	 * of the current logged in Account entity will be retrieved from session.
	 *
	 * @param Person $person The Person entity to be searched for
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Meeting[]
	 */
	public function findAllowed(Person $person = NULL) {
		if ($person === NULL) {
			$person = $this->getPerson();
		}

		$query = $this->createQuery();
		$query->matching($query->equals('invitations.participant', $person));
		return $query->execute();
	}

	/**
	 * Finds allowed Meeting entities having open/unanswered invitations.
	 *
	 * If the $person argument is not submitted, the Person entity
	 * of the current logged in Account entity will be retrieved from session.
	 *
	 * @param Person $person The Person entity to be searched for
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Meeting[]
	 */
	public function findAllowedWithOpenInvitations(Person $person = NULL) {
		if ($person === NULL) {
			$person = $this->getPerson();
		}

		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('status', \OpenAgenda\Application\Domain\Model\Meeting::STATUS_COMMITTED),
				$query->equals('invitations.participant', $person),
				$query->equals('invitations.status', 0)
			)
		);
		return $query->execute();
	}

}