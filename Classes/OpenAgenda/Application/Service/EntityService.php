<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class EntityService
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
	 * @param object $subject
	 * @param bool $cascade
	 */
	public function applyStatusDates($subject, $cascade = TRUE) {
		$this->applyCreationDate($subject, $cascade);
		$this->applyModificationDate($subject, $cascade);
	}

	/**
	 * @param object $subject
	 * @param bool $cascade
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
	 * @param object $subject
	 * @param bool $cascade
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
	 * @param \Doctrine\Common\Collections\Collection $collection
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
	 * @param object $subject
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