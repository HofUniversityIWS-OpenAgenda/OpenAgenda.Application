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
 * @package OpenAgenda\Application\Service
 * @Flow\Scope("singleton")
 * @author Oliver Hader <oliver@typo3.org>
 */
class ObjectService {

	const PATTERN_Components = '#^(?P<className>[^>-]+)(?:->(?P<methodName>[^(]+))\((?P<arguments>[^)]+)\)#';

	protected $componentFilter = array(
		'className', 'methodName', 'arguments'
	);

	/**
	 * @param string $stringCallback
	 * @param mixed $subject
	 * @return mixed|NULL
	 * @throws \TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException
	 */
	public function executeStringCallback($stringCallback, $subject) {
		$components = $this->getComponents($stringCallback);

		if ($components === NULL) {
			throw new \RuntimeException('Could not parse string callback "' . $stringCallback . '"');
		}

		$arguments = $this->substituteStringVariables($components['arguments'], $subject);
		$callable = array($components['className']);

		if (!empty($components['methodName'])) {
			if ($components['className'] === '$self') {
				$object = $subject;
			} else {
				$object = \TYPO3\Flow\Core\Bootstrap::$staticObjectManager->get($components['className']);
			}

			if ($object === NULL) {
				return NULL;
			}

			$callable = array($object, $components['methodName']);
		}

		return call_user_func_array($callable, $arguments);
	}

	/**
	 * @param array $variables
	 * @param mixed $subject
	 * @return array
	 * @throws \TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException
	 */
	public function substituteStringVariables(array $variables, $subject) {
		$substitutedVariables = array();

		foreach ($variables as $key => $value) {
			if ($value{0} === '$') {
				$variableName = substr($value, 1);
				$value = ObjectAccess::getProperty($subject, $variableName);
			}
			$substitutedVariables[$key] = $value;
		}

		return $substitutedVariables;
	}

	/**
	 * @param string $stringCallback
	 * @return array|NULL
	 */
	protected function getComponents($stringCallback) {
		if (!preg_match(static::PATTERN_Components, $stringCallback, $matches)) {
			return NULL;
		}

		if (isset($matches['arguments'])) {
			$matches['arguments'] = array_map(
				'trim',
				explode(',', $matches['arguments'])
			);
		}

		return array_intersect_key(
			$matches,
			array_flip($this->componentFilter)
		);
	}

}