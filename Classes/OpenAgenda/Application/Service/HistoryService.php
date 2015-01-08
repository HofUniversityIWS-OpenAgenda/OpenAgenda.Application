<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\History;

/**
 * Class HistoryService
 *
 * This service creates history entities on creating or modification
 * of a related objective subject and keeps track of its changes and times.
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service
 * @author Oliver Hader <oliver@typo3.org>
 */
class HistoryService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\HistoryRepository
	 */
	protected $historyRepository;

	/**
	 * @Flow\Inject
	 * @var EntityService
	 */
	protected $entityService;

	/**
	 * Invokes the creation of history entities for a given related objective entity.
	 *
	 * @author Oliver Hader <oliver@typo3.org>
	 * @author Andreas Steiger <andreas.steiger@hof-university.de>
	 * @param object $subject The related objective entity
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function invoke($subject) {
		$this->entityService->applyModificationDate($subject);

		$history = new History();
		$history->setCreationDate(new \DateTime());
		$history->setEntityType(get_class($subject));
		if ($this->securityContext->isInitialized()) {
			$history->setIssuer($this->securityContext->getParty());
		}
		$history->setEntityIdentifier($this->persistenceManager->getIdentifierByObject($subject));
		// @todo Determine changes in current modified object and persisted entity
		// $history->setPreviousData(serialize($subject));
		$this->historyRepository->add($history);
	}

}