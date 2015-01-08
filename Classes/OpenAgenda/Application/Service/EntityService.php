<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class EntityService
 *
 * This service provides that for domain entities.
 *
 * @package OpenAgenda\Application\Service
 * @Flow\Scope("singleton")
 * @author Oliver Hader <oliver@typo3.org>
 */
class EntityService {

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Applies status dates, such as creating time or modification time.
	 *
	 * @param object $subject The entity to be extended
	 * @param bool $cascade Whether to cascade into child entities
	 * @return void
	 */
	public function applyStatusDates($subject, $cascade = TRUE) {
		$this->applyCreationDate($subject, $cascade);
		$this->applyModificationDate($subject, $cascade);
	}

	/**
	 * Applies the modification time.
	 *
	 * @param object $subject The entity to be extended
	 * @param bool $cascade Whether to cascade into child entities
	 * @return void
	 */
	public function applyModificationDate($subject, $cascade = TRUE) {
		if ($subject instanceof \OpenAgenda\Application\Domain\Model\ModificationInterface) {
			$subject->setModificationDate(new \DateTime());
		}

		if ($cascade) {
			foreach ($this->getCollectionProperties($subject) as $collection) {
				foreach ($collection as $child) {
					$this->applyModificationDate($child, $cascade);
				}
			}
		}
	}

	/**
	 * Applies the creation time.
	 *
	 * @param object $subject The entity to be extended
	 * @param bool $cascade Whether to cascade into child entities
	 * @return void
	 */
	public function applyCreationDate($subject, $cascade = TRUE) {
		if ($subject instanceof \OpenAgenda\Application\Domain\Model\CreationInterface && $this->persistenceManager->isNewObject($subject)) {
			$subject->setCreationDate(new \DateTime());
		}

		if ($cascade) {
			foreach ($this->getCollectionProperties($subject) as $collection) {
				foreach ($collection as $child) {
					$this->applyCreationDate($child, $cascade);
				}
			}
		}
	}

	/**
	 * Applies sorting order to entities held in a collection.
	 * Doctrine2 collections are not able yet to annotate a sorting property.
	 *
	 * @param \Doctrine\Common\Collections\Collection $collection
	 * @return void
	 */
	public function applySortingOrder(\Doctrine\Common\Collections\Collection $collection) {
		$sorting = 0;
		foreach ($collection as $subject) {
			if ($subject instanceof \OpenAgenda\Application\Domain\Model\SortableInterface) {
				$subject->setSorting(++$sorting);
			}
		}
	}

	/**
	 * Determines properties of a given entity that are using Doctrine2 collections.
	 *
	 * @param object $subject The entity to be worked on
	 * @return array|\Doctrine\Common\Collections\Collection[]
	 */
	protected function getCollectionProperties($subject) {
		$properties = array();
		$className = get_class($subject);
		$propertyNames = $this->reflectionService->getClassPropertyNames($className);

		foreach ($propertyNames as $propertyName) {
			try {
				$propertyValue = ObjectAccess::getProperty($subject, $propertyName);
			} catch (\TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException $exception) {
				continue;
			}

			if ($propertyValue instanceof \Doctrine\Common\Collections\Collection) {
				$properties[$propertyName] = $propertyValue;
			}
		}

		return $properties;
	}

}