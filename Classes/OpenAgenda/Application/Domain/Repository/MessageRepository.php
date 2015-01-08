<?php
namespace OpenAgenda\Application\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class MessageRepository
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Domain\Repository
 * @author Oliver Hader <oliver@typo3.org>
 */
class MessageRepository extends AbstractRepository {

	/**
	 * Finds Message entities by a given status.
	 *
	 * @param int|array $status Status value(s) to be searched for
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface|\OpenAgenda\Application\Domain\Model\Message[]
	 */
	public function findByStatus($status) {
		if (!is_array($status)) {
			$status = array($status);
		}

		$query = $this->createQuery();
		$query->matching($query->in('status', $status));
		return $query->execute();
	}

}