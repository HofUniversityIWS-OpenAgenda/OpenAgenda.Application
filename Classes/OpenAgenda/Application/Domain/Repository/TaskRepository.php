<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;
use OpenAgenda\Application\Domain\Model\Person;
use OpenAgenda\Application\Domain\Model\Task;

/**
 * Class TaskRepository
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Domain\Repository
 * @author Oliver Hader <oliver@typo3.org>
 */
class TaskRepository extends AbstractRepository {

	/**
	 * @Flow\Inject
	 * @var MeetingRepository
	 */
	protected $meetingRepository;

	/**
	 * @param Person $person
	 * @param bool $excludeAssigned Exclude if assigned to $person
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|Task[]
	 */
	public function findAllowed(Person $person = NULL, $excludeAssigned = FALSE) {
		if ($person === NULL) {
			$person = $this->getPerson();
		}

		$query = $this->createQuery();

		if ($excludeAssigned) {
			$constraint = $query->logicalAnd(
				$this->getAllowedConstraint($query, $person),
				$query->logicalNot($query->equals('assignee', $person))
			);
		} else {
			$constraint = $this->getAllowedConstraint($query, $person);
		}

		$query->matching($constraint);
		return $query->execute();
	}

	/**
	 * @param Person $person
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|Task[]
	 */
	public function findByPerson(Person $person = NULL) {
		if ($person === NULL) {
			$person = $this->getPerson();
		}
		$query = $this->createQuery();
		$query->matching($query->equals('assignee', $person));
		return $query->execute();
	}

	/**
	 * @param Meeting $meeting
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|Task[]
	 */
	public function findByMeeting(Meeting $meeting) {
		$query = $this->createQuery();
		$query->matching($query->equals('meeting', $meeting));
		return $query->execute();
	}

	/**
	 * @param \TYPO3\Flow\Persistence\QueryInterface $query
	 * @param Person $person
	 * @return \TYPO3\Flow\Persistence\Generic\Qom\Constraint
	 */
	protected function getAllowedConstraint(\TYPO3\Flow\Persistence\QueryInterface $query, Person $person) {
		$meetings = array();
		foreach ($this->meetingRepository->findAllowed($person) as $meeting) {
			$meetings[] = $this->identify($meeting);
		}
		return $query->in('meeting', $meetings);
	}

}