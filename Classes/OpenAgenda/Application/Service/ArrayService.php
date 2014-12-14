<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ArrayService
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service
 * @author Oliver Hader <oliver@typo3.org>
 */
class ArrayService {

	const ANNOTATION_Entity = 'TYPO3\\Flow\\Annotations\\Entity';
	const ANNOTATION_Transient = 'TYPO3\\Flow\\Annotations\\Transient';
	const ANNOTATION_ToArray = 'OpenAgenda\\Application\\Framework\\Annotations\\ToFlatArray';

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var ObjectService
	 * @Flow\Inject
	 */
	protected $objectService;

	/**
	 * @param mixed $source
	 * @param string $scopeName
	 * @return array
	 */
	public function flatten($source, $scopeName = NULL) {
		if ($this->canIterate($source)) {
			return $this->flattenIterator($source, $scopeName);
		} else {
			return $this->flattenObject($source, $scopeName);
		}
	}

	/**
	 * @param object $source
	 * @param string $scopeName
	 * @return array
	 */
	public function flattenObject($source, $scopeName = NULL) {
		$target = array();
		$className = get_class($source);

		$classAnnotation = $this->reflectionService->getClassAnnotation($className, static::ANNOTATION_Entity);
		if ($classAnnotation !== NULL) {
			$target['__identity'] = $this->persistenceManager->getIdentifierByObject($source);
		}

		$propertyNames = $this->reflectionService->getPropertyNamesByAnnotation(
			$className,
			static::ANNOTATION_ToArray
		);

		foreach ($propertyNames as $propertyName) {
			$propertyValue = NULL;

			/** @var Flow\Transient $transientAnnotation */
			$transientAnnotation = $this->reflectionService->getPropertyAnnotation(
				$className,
				$propertyName,
				static::ANNOTATION_Transient
			);

			/** @var \OpenAgenda\Application\Framework\Annotations\ToFlatArray $toArrayAnnotation */
			$toArrayAnnotation = $this->reflectionService->getPropertyAnnotation(
				$className,
				$propertyName,
				static::ANNOTATION_ToArray
			);

			// Skip property if requested scope name is not defined for the current entity property
			$propertyScopeNames = $toArrayAnnotation->getScopeNames();
			if ($propertyScopeNames !== NULL && $scopeName !== NULL && !in_array($scopeName, $propertyScopeNames)) {
				continue;
			}

			// Get property value
			if ($transientAnnotation === NULL) {
				$propertyValue = ObjectAccess::getProperty($source, $propertyName);
			// Use basic source subject if property is transient and does not contains anything
			} else {
				$propertyValue = $source;
			}

			if ($toArrayAnnotation->getUseIdentifier()) {
				$propertyValue = $this->persistenceManager->getIdentifierByObject($propertyValue);
			} elseif ($toArrayAnnotation->getCallback() !== NULL) {
				$propertyValue = $this->objectService->executeStringCallback(
					$toArrayAnnotation->getCallback(),
					$propertyValue
				);
			}

			if ($this->canDescend($propertyValue)) {
				$propertyValue = $this->flatten($propertyValue, $scopeName);
			}

			$target[$propertyName] = $propertyValue;
		}

		return $target;
	}

	/**
	 * @param \Iterator|\ArrayAccess $source
	 * @param string $scopeName
	 * @return array
	 */
	public function flattenIterator($source, $scopeName) {
		if (!$this->canIterate($source)) {
			throw new \RuntimeException(
				'"' . get_class($source) . '" cannot be iterated',
				1416993624
			);
		}

		$flatArray = array();
		foreach ($source as $key => $value) {
			if ($this->canDescend($value)) {
				$value = $this->flatten($value, $scopeName);
			}
			$flatArray[$key] = $value;
		}
		return $flatArray;
	}

	/**
	 * @param mixed $source
	 * @return bool
	 */
	protected function canDescend($source) {
		return (is_object($source) || $this->canIterate($source));
	}

	/**
	 * @param mixed $source
	 * @return bool
	 */
	protected function canIterate($source) {
		return (is_array($source) || $source instanceof \Iterator || $source instanceof \ArrayAccess);
	}

}