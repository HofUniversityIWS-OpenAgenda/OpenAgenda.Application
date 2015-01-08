<?php
namespace OpenAgenda\Application\Service\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Property\PropertyMappingConfiguration;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

/**
 * Class PropertyMappingService
 *
 * Since the automatic HMAC signing of form values cannot be used
 * by transporting data from AngularJS by plain JSON, the accordant
 * properties and sub-properties of submitted data needs to be defined
 * in a different way.
 *
 * The property mapping process in invoked in TYPO3 Flow's MVC controllers
 * to create proper domain entities out of the submitted data.
 *
 * **Settings**
 *
 * *in Configuration/Settings.yaml or any other context specific
 * configuration file of the global TYPO3 Flow instance*
 *
 * <code>
 * OpenAgenda:
 *   Application:
 *     PropertyMapping:
 *       <class name of the domain entity to be mapped>:
 *         <action name in accordant controller>:
 *           <name of the account role - or 'default' for anybody>:
 *             allow: <array of properties and sub-properties - or '*' for everything>
 *             types: <array of allowed mapping actions - either 'create' or 'modify'>
 * </code>
 *
 * **Example**
 *
 * <code>
 * OpenAgenda:
 *   Application:
 *     PropertyMapping:
 *       OpenAgenda\Application\Domain\Model\Task:
 *         create:
 *           'OpenAgenda.Application:MeetingManager':
 *             allow: '*'
 *             types: ['create']
 *         update:
 *           default:
 *             allow: ['status','child.property']
 *             types: ['modify']
 *           'OpenAgenda.Application:MeetingManager':
 *             allow: '*'
 *             types: ['modify']
 * </code>
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
	 * Walks over each controller argument and applies the mapping configuration.
	 *
	 * @param \TYPO3\Flow\Mvc\Controller\Arguments $arguments Controller arguments
	 * @param string $actionName Current controller action name for with arguments shall be used
	 */
	public function configure(\TYPO3\Flow\Mvc\Controller\Arguments $arguments, $actionName) {
		foreach ($arguments as $argument) {
			$this->configureArgument($argument, $actionName);
		}
	}

	/**
	 * Applies the mapping configuration to a particular controller argument.
	 *
	 * @param \TYPO3\Flow\Mvc\Controller\Argument $argument Controller argument
	 * @param string $actionName Current controller action name for with arguments shall be used
	 */
	public function configureArgument(\TYPO3\Flow\Mvc\Controller\Argument $argument, $actionName) {
		$dataType = $argument->getDataType();

		$settings = $this->determineSettings($argument, $actionName);
		if ($settings === NULL) {
			return;
		}

		$options = array();
		$allowedPropertyNames = array();
		$propertyNames = $this->reflectionService->getClassPropertyNames($dataType);

		if (isset($settings['types']) && is_array($settings['types'])) {
			if (in_array('create', $settings['types'])) {
				$options[PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED] = TRUE;
			}
			if (in_array('modify', $settings['types'])) {
				$options[PersistentObjectConverter::CONFIGURATION_MODIFICATION_ALLOWED] = TRUE;
			}
		}

		if (isset($settings['allow'])) {
			if ($settings['allow'] === '*') {
				$allowedPropertyNames = $propertyNames;
			} else {
				$allowedPropertyNames = $settings['allow'];
			}
		}

		$this->applyConfiguration(
			$argument->getPropertyMappingConfiguration(),
			$allowedPropertyNames,
			$options,
			$dataType
		);
	}

	/**
	 * Applies configuration for allowed properties.
	 * This method is called recursively for sub-properties.
	 *
	 * @param PropertyMappingConfiguration $configuration The mapping configuration for the base controller argument
	 * @param array $allowedPropertyNames Allowed property names (including sub-properties - if any)
	 * @param array $options Global options to allow creating and/or modification
	 * @param string $dataType Data type (= class name) of the calling parent property
	 */
	protected function applyConfiguration(PropertyMappingConfiguration $configuration, array $allowedPropertyNames, array $options, $dataType) {
		$propertyNames = $this->reflectionService->getClassPropertyNames($dataType);

		list($allowedLevelPropertyNames, $allowedPropertyNamesForSubProperties) = $this->separateLevels($allowedPropertyNames);
		$allowedLevelPropertyNames = array_intersect($propertyNames, $allowedLevelPropertyNames);
		$deniedLevelPropertyNames = array_diff($propertyNames, $allowedLevelPropertyNames);

		call_user_func_array(array($configuration, 'skipProperties'), $deniedLevelPropertyNames);
		call_user_func_array(array($configuration, 'allowProperties'), $allowedLevelPropertyNames);

		if (!empty($options)) {
			$configuration->setTypeConverterOptions('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', $options);
		}

		foreach ($allowedPropertyNamesForSubProperties as $subPropertyName => $allowedSubPropertyNames) {
			if (!in_array($subPropertyName, $allowedLevelPropertyNames)) {
				continue;
			}

			$classSchema = $this->reflectionService->getClassSchema($dataType);
			$propertySchema = $classSchema->getProperty($subPropertyName);
			if (empty($propertySchema['type'])) {
				continue;
			}

			$this->applyConfiguration(
				$configuration->forProperty($subPropertyName),
				$allowedSubPropertyNames,
				$options,
				$propertySchema['type']
			);
		}
	}

	/**
	 * Separates property names into accordant levels.
	 *
	 * **Example**
	 *
	 * <code>
	 * array('first', 'first.something', 'second.third', 'fourth')
	 * </code>
	 *
	 * will result in
	 *
	 * <code>
	 * array(
	 *   array('first', 'second', 'fourth'),
	 *   array('first' => array('something'), 'second' => array('third'))
	 * )
	 * </code>
	 *
	 * @param array $propertyNames Property names to be separated
	 * @return array Separated properties - first index contains current main level
	 */
	protected function separateLevels(array $propertyNames) {
		$allowedLevelPropertyNames = array();
		$allowedPropertyNamesForSubProperties = array();

		foreach ($propertyNames as $propertyName) {
			if (strpos($propertyName, '.') === FALSE) {
				$allowedLevelPropertyNames[] = $propertyName;
			} else {
				list($levelPart, $subPart) = explode('.', $propertyName, 2);
				$allowedLevelPropertyNames[] = $levelPart;
				$allowedPropertyNamesForSubProperties[$levelPart][] = $subPart;
			}
		}

		return array(
			$allowedLevelPropertyNames,
			$allowedPropertyNamesForSubProperties
		);
	}

	/**
	 * Determines settings for a given controller argument.
	 * These settings depend on the entity to be mapped, roles of the current Account entity
	 * and the calling controller action name that will later on use the mapped properties.
	 *
	 * @param \TYPO3\Flow\Mvc\Controller\Argument $argument Controller argument
	 * @param string $actionName Current controller action name for with arguments shall be used
	 * @return NULL|array Settings array or NULL if none where found
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
			$matchingRole = array_shift($matchingRoles);
			$settings = $this->propertyMappingSettings[$dataType][$actionName][$matchingRole];
		} elseif (isset($this->propertyMappingSettings[$dataType][$actionName]['default'])) {
			$settings = $this->propertyMappingSettings[$dataType][$actionName]['default'];
		}

		return $settings;
	}

}