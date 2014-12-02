<?php
namespace OpenAgenda\Application\Routing;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class EntityHandler
 *
 * @package OpenAgenda\Application\Service
 * @author Oliver Hader <oliver@typo3.org>
 */
class EntityHandler extends \TYPO3\Flow\Mvc\Routing\DynamicRoutePart {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * Checks whether the current URI section matches the configured RegEx pattern.
	 *
	 * @param string $requestPath Value to be verified
	 * @return bool TRUE if value could be matched successfully, otherwise FALSE.
	 * @throws \TYPO3\Flow\Exception
	 */
	protected function matchValue($requestPath) {
		if (!isset($this->options['objectType'])) {
			throw new \TYPO3\Flow\Exception('Configuration property objectType must be set', 1417522206);
		}

		$entity = $this->persistenceManager->getObjectByIdentifier($requestPath, $this->options['objectType']);
		if ($entity === NULL) {
			if (!empty($this->options['processInvalid'])) {
				$this->value = NULL;
				return TRUE;
			}
			return FALSE;
		}

		$this->value = $requestPath;
		return TRUE;
	}

	/**
	 * Resolves the value to be used in URI path.
	 *
	 * @param string $value Value to be converted into an URI
	 * @return bool TRUE if value could be resolved successfully, otherwise FALSE.
	 */
	protected function resolveValue($value) {
		if (!is_object($value)) {
			return FALSE;
		}

		$identifier = $this->persistenceManager->getIdentifierByObject($value);
		if ($identifier === NULL) {
			return FALSE;
		}

		$this->value = $identifier;
		return TRUE;
	}

}