<?php
namespace OpenAgenda\Application\Service\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

/**
 * Class PropertyMappingService
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Security
 * @author Oliver Hader <oliver@typo3.org>
 */
class PropertyMappingService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var array
	 * @Flow\Inject(setting="PropertyMapping")
	 */
	protected $propertyMappingSettings;

	/**
	 * @param \TYPO3\Flow\Mvc\Controller\Arguments $arguments
	 * @param string $actionName
	 */
	public function configure(\TYPO3\Flow\Mvc\Controller\Arguments $arguments, $actionName) {
		foreach ($arguments as $argument) {
			$this->configureArgument($argument, $actionName);
		}
	}

	/**
	 * @param \TYPO3\Flow\Mvc\Controller\Argument $argument
	 * @param string $actionName
	 */
	public function configureArgument(\TYPO3\Flow\Mvc\Controller\Argument $argument, $actionName) {
		$dataType = $argument->getDataType();

		$settings = $this->determineSettings($argument, $actionName);
		if ($settings === NULL) {
			return;
		}

		$options = array();
		$allowedPropertyNames = array();
		$deniedPropertyNames = array();
		$propertyNames = $this->reflectionService->getClassPropertyNames($dataType);

		if (isset($settings['allow'])) {
			if ($settings['allow'] === '*') {
				$allowedPropertyNames = $propertyNames;
			} else {
				$allowedPropertyNames = array_intersect($propertyNames, $settings['allow']);
				$deniedPropertyNames = array_diff($propertyNames, $settings['allow']);
			}
		}

		if (isset($settings['types']) && is_array($settings['types'])) {
			if (in_array('create', $settings['types'])) {
				$options[PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED] = TRUE;
			}
			if (in_array('modify', $settings['types'])) {
				$options[PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED] = TRUE;
			}
		}

		$propertyMappingConfiguration = $argument->getPropertyMappingConfiguration();
		call_user_func_array(array($propertyMappingConfiguration, 'skipProperties'), $deniedPropertyNames);
		call_user_func_array(array($propertyMappingConfiguration, 'allowProperties'), $allowedPropertyNames);

		if (!empty($options)) {
			$propertyMappingConfiguration
				->setTypeConverterOptions('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', $options);
		}
	}

	/**
	 * @param \TYPO3\Flow\Mvc\Controller\Argument $argument
	 * @param string $actionName
	 * @return NULL|array
	 */
	protected function determineSettings(\TYPO3\Flow\Mvc\Controller\Argument $argument, $actionName) {
		$settings = NULL;

		$dataType = $argument->getDataType();
		if (!isset($this->propertyMappingSettings[$dataType][$actionName])) {
			return $settings;
		}

		$roles = array_keys($this->propertyMappingSettings[$dataType][$actionName]);
		$matchingRoles = array_intersect($roles, $this->securityContext->getRoles());

		if (count($matchingRoles)) {
			$matchingRole = $matchingRoles[0];
			$settings = $this->propertyMappingSettings[$dataType][$actionName][$matchingRole];
		} elseif (isset($this->propertyMappingSettings[$dataType][$actionName]['default'])) {
			$settings = $this->propertyMappingSettings[$dataType][$actionName]['default'];
		}

		return $settings;
	}

}