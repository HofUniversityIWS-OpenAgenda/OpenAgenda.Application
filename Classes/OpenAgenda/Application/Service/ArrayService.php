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
		$className = $this->resolveClassName($source);

		$classAnnotation = $this->reflectionService->getClassAnnotation($className, static::ANNOTATION_Entity);
		if ($classAnnotation !== NULL) {
			$target['__identity'] = $this->persistenceManager->getIdentifierByObject($source);
		}

		$target = array_merge($target, $this->processClassAnnotations($source, $scopeName));
		$target = array_merge($target, $this->processPropertyAnnotations($source, $scopeName));

		return $target;
	}

	protected function processPropertyAnnotations($source, $scopeName = NULL) {
		$target = array();
		$className = $this->resolveClassName($source);

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
			if (!$this->validateScopeName($toArrayAnnotation, $scopeName)) {
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
				if (is_object($propertyValue)) {
					$propertyValue = $this->persistenceManager->getIdentifierByObject($propertyValue);
				} else {
					$propertyValue = NULL;
				}
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

	protected function processClassAnnotations($source, $scopeName = NULL) {
		$target = array();
		$className = $this->resolveClassName($source);

		$classAnnotations = $this->reflectionService->getClassAnnotations(
			$className,
			static::ANNOTATION_ToArray
		);

		/** @var \OpenAgenda\Application\Framework\Annotations\ToFlatArray $toArrayAnnotation */
		foreach ($classAnnotations as $toArrayAnnotation) {
			if ($toArrayAnnotation->getTransientName() === NULL || $toArrayAnnotation->getCallback() === NULL) {
				continue;
			}

			// Skip property if requested scope name is not defined for the current entity property
			if (!$this->validateScopeName($toArrayAnnotation, $scopeName)) {
				continue;
			}

			$transientName = $toArrayAnnotation->getTransientName();
			$transientValue = $this->objectService->executeStringCallback(
				$toArrayAnnotation->getCallback(),
				$source
			);
			$target[$transientName] = $transientValue;
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

	/**
	 * @param mixed $subject
	 * @return mixed
	 */
	public function prepare($subject) {
		if ($subject instanceof \TYPO3\Party\Domain\Model\Person) {
			$result = $this->preparePerson($subject);
		} else {
			$result = $this->flatten($subject, 'prepare');
		}

		return $result;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @return array
	 */
	protected function preparePerson(\TYPO3\Party\Domain\Model\Person $person) {
		return array(
			'__identity' => $this->persistenceManager->getIdentifierByObject($person),
			'name' => array(
				'firstName' => $person->getName()->getFirstName(),
				'lastName' => $person->getName()->getLastName(),
			),
			'mail' => $person->getPrimaryElectronicAddress()->getIdentifier(),
		);
	}

	/**
	 * @param object $source
	 * @return string
	 */
	protected function resolveClassName($source) {
		$className = get_class($source);
		$reflectionClass = new \ReflectionClass($className);

		while ($reflectionClass->implementsInterface('Doctrine\ORM\\Proxy\\Proxy')) {
			$reflectionClass = $reflectionClass->getParentClass();
			$className = $reflectionClass->getName();
		}

		return $className;
	}

	/**
	 * @param \OpenAgenda\Application\Framework\Annotations\ToFlatArray $annotation
	 * @param string|NULL $scopeName
	 * @return bool
	 */
	protected function validateScopeName(\OpenAgenda\Application\Framework\Annotations\ToFlatArray $annotation, $scopeName) {
		// Skip property if requested scope name is not defined for the current entity property
		$scopeNames = $annotation->getScopeNames();
		$denyScopeNames = $annotation->getDenyScopeNames();

		if (!empty($scopeNames) && $scopeName !== NULL && !in_array($scopeName, $scopeNames)) {
			return FALSE;
		}

		if (!empty($denyScopeNames) && $scopeName !== NULL && in_array($scopeName, $denyScopeNames)) {
			return FALSE;
		}

		return TRUE;
	}

}