<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use OpenAgenda\Application\Domain\Model\Meeting;
use OpenAgenda\Application\Domain\Model\AgendaItem;
use OpenAgenda\Application\Domain\Model\Invitation;
use OpenAgenda\Application\Domain\Model\Task;

/**
 * Class AgendaItemRepository
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Domain\Repository
 * @author Andreas Steiger <<andreas.steiger@hof-university.de>
 */
class AgendaItemRepository extends AbstractRepository {

	/**
	 * @param Meeting $meeting
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|AgendaItem[]
	 */
	public function findByMeeting(Meeting $meeting) {
		$query = $this->createQuery();
		$query->matching($query->equals('meeting', $meeting));
		return $query->execute();
	}

}