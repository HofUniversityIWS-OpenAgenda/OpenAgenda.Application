<?php
namespace OpenAgenda\Application\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ObjectService
 *
 * This service provides additional functionality if working on objects.
 *
 * @package OpenAgenda\Application\Service
 * @Flow\Scope("singleton")
 * @author Oliver Hader <oliver@typo3.org>
 */
class ObjectService {

	const PATTERN_Components = '#^(?P<className>[^>-]+)(?:->(?P<methodName>[^(]+))\((?P<arguments>[^)]*)\)#';

	/**
	 * @var array
	 */
	protected $componentFilter = array(
		'className', 'methodName', 'arguments'
	);

	/**
	 * Executes a callback on a given object.
	 *
	 * @param string $stringCallback The callback to be executed
	 * @param mixed $subject The related object to be used
	 * @return mixed|NULL The result of the callback
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
	 * Substitutes string variables for a given object.
	 *
	 * + $self will be substituted with the given $subject
	 * + $anythingElse will be substituted with the result of $subject->getAnythingElse()
	 *
	 * @param array $variables The variables to be used for substitution
	 * @param mixed $subject The object to be worked on
	 * @return array The substituted variables
	 * @throws \TYPO3\Flow\Reflection\Exception\PropertyNotAccessibleException
	 */
	public function substituteStringVariables(array $variables, $subject) {
		$substitutedVariables = array();

		foreach ($variables as $key => $value) {
			if ($value === '$self') {
				$value = $subject;
			} elseif (is_string($value) && substr($value, 0, 1) === '$') {
				$variableName = substr($value, 1);
				$value = ObjectAccess::getProperty($subject, $variableName);
			}
			$substitutedVariables[$key] = $value;
		}

		return $substitutedVariables;
	}

	/**
	 * Extracts the components of a string callback.
	 * The resulting array contains "className", "methodName" and "arguments".
	 *
	 * **Example**
	 *
	 * the callback
	 *
	 * <code>
	 * $self->format('c')
	 * </code>
	 *
	 * will result in
	 *
	 * <code>
	 * array("className" => "$self", "methodName" => "format", "arguments" => array("c"))
	 * </code>
	 *
	 * @param string $stringCallback
	 * @return array|NULL The extracted components or NULL if parsing failed
	 */
	protected function getComponents($stringCallback) {
		if (!preg_match(static::PATTERN_Components, $stringCallback, $matches)) {
			return NULL;
		}

		if (!empty($matches['arguments'])) {
			$matches['arguments'] = explode(',', $matches['arguments']);
			$matches['arguments'] = array_map('trim', $matches['arguments']);
			$matches['arguments'] = array_map(array($this, 'castValue'), $matches['arguments']);
		} else {
			$matches['arguments'] = array();
		}

		return array_intersect_key(
			$matches,
			array_flip($this->componentFilter)
		);
	}

	/**
	 * Casts type values explicitly.
	 * If value is wrapped by quotes, string cast is applied.
	 *
	 * @param mixed $value The value to be casted
	 * @return float|int|string
	 */
	protected function castValue($value) {
		if (substr($value, 0, 1) === "'" && substr($value, -1) === "'"
			|| substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
			$value = substr($value, 1, -1);
		} elseif ((string)$value === (string)(int)$value) {
			$value = (int)$value;
		} elseif ((string)$value === (string)(float)$value) {
			$value = (float)$value;
		}

		return $value;
	}

}