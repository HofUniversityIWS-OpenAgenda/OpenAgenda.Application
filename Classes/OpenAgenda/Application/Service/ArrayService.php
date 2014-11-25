<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ObjectService
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service
 * @author Oliver Hader <oliver@typo3.org>
 */
class ArrayService {

	const ANNOTATION_ToArray = 'OpenAgenda\\Application\\Annotations\\ToArray';

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var ObjectService
	 * @Flow\Inject
	 */
	protected $objectService;

	/**
	 * @param mixed $source
	 * @return array
	 */
	public function convert($source) {
		$target = array();
		$className = get_class($source);

		$propertyNames = $this->reflectionService->getPropertyNamesByAnnotation(
			$className,
			static::ANNOTATION_ToArray
		);

		foreach ($propertyNames as $propertyName) {
			/** @var \OpenAgenda\Application\Annotations\ToArray $propertyAnnotation */
			$propertyAnnotation = $this->reflectionService->getPropertyAnnotation(
				$className,
				$propertyName,
				static::ANNOTATION_ToArray
			);
			$propertyValue = ObjectAccess::getProperty($source, $propertyName);

			if ($propertyAnnotation->getCallback() !== NULL) {
				$propertyValue = $this->objectService->executeStringCallback(
					$propertyAnnotation->getCallback(),
					$propertyValue
				);
			}

			$target[$propertyName] = $propertyValue;
		}

		return $target;
	}

}