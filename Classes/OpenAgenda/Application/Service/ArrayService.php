<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ArrayService
 *
 * This service transforms complex object and entity structures
 * into simpler flat array, that can be converted to JSON then.
 *
 * The \OpenAgenda\Application\Framework\Annotations\ToFlatArray ("OA\ToFlatArray")
 * annotation is essential for controlling scope, callbacks and general triggering.
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service
 * @see \OpenAgenda\Application\Framework\Annotations\ToFlatArray
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
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Flattens any object as array.
	 *
	 * @param mixed $source Object to be flattened
	 * @param string $scopeName Optional scope name that annotations will be compared against
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
	 * Flattens a real object/entity as array
	 *
	 * @param object $source Object to be flattened
	 * @param string $scopeName Optional scope name that annotations will be compared against
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

	/**
	 * Processes and interprets annotations bound to properties of a source object.
	 *
	 * @param object $source The source object to be worked on
	 * @param NULL|string $scopeName Optional scope name that annotations will be compared against
	 * @return array
	 * @throws \TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException
	 */
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

	/**
	 * Processes and interprets annotations bound the class definition of a source object.
	 *
	 * @param object $source The source object to be worked on
	 * @param NULL|string $scopeName Optional scope name that annotations will be compared against
	 * @return array
	 */
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
	 * Flattens an array-like object or array.
	 *
	 * @param \Iterator|\ArrayAccess $source
	 * @param string $scopeName Optional scope name that annotations will be compared against
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
	 * Determines whether an object can descend to child objects.
	 *
	 * @param mixed $source The source to be checked
	 * @return bool
	 */
	protected function canDescend($source) {
		return (is_object($source) || $this->canIterate($source));
	}

	/**
	 * Determines whether an object can be iterated.
	 *
	 * @param mixed $source The source to be checked
	 * @return bool
	 */
	protected function canIterate($source) {
		return (is_array($source) || $source instanceof \Iterator || $source instanceof \ArrayAccess);
	}

	/**
	 * Prepares an object to be used as array.
	 *
	 * @param mixed $subject The subject be used
	 * @return array
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
	 * Prepares a Person entity to be used as array.
	 *
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @return array
	 */
	protected function preparePerson(\TYPO3\Party\Domain\Model\Person $person) {
		$preparation = array(
			'__identity' => $this->persistenceManager->getIdentifierByObject($person),
			'name' => array(
				'__identity' => $this->persistenceManager->getIdentifierByObject($person->getName()),
				'firstName' => $person->getName()->getFirstName(),
				'lastName' => $person->getName()->getLastName(),
			),
			'primaryElectronicAddress' => array(
				'__identity' => $this->persistenceManager->getIdentifierByObject($person->getPrimaryElectronicAddress()),
				'identifier' => $person->getPrimaryElectronicAddress()->getIdentifier(),
			),
			'$mail' => $person->getPrimaryElectronicAddress()->getIdentifier(),
		);

		if ($this->securityContext->getParty() === $person) {
			$preparation['$currentProvider'] = $this->securityContext->getAccount()->getAuthenticationProviderName();
		}

		if ($person instanceof \OpenAgenda\Application\Domain\Model\Person) {
			$preparation['phoneNumber'] = $person->getPhoneNumber();
		}

		return $preparation;
	}

	/**
	 * Resolves the base class name to overcome generated Doctrine AOP proxy classes.

	 * @param object $source The source to be worked on
	 * @return string The resolved base class name
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
	 * Validates a scope name against a given OA\ToFlatArray annotation.
	 * If a given scope shall be processed, TRUE is returned - otherwise it's FALSE.
	 *
	 * @param \OpenAgenda\Application\Framework\Annotations\ToFlatArray $annotation
	 * @param string|NULL $scopeName Scope name to be validated
	 * @return bool Whether to process the given scope
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