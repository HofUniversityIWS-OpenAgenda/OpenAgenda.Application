<?php
namespace OpenAgenda\Application\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class ModelScopeValidator
 *
 * This validator utilizes settings to aggregate more specific
 * validators to be used on a particular model entity in a particular scope.
 *
 * **Usage**
 *
 * `@Flow\Validate(argumentName="newAccount", type="OpenAgenda.Application:ModelScope", options={"scopeName"="create"})`
 *
 * **Settings**
 *
 * *in Configuration/Settings.yaml or any other context specific
 * configuration file of the global TYPO3 Flow instance*
 *
 * `
 * OpenAgenda:
 *   Application:
 *     Validation:
 *       ModelScopeValidator:
 *         <class name of domain entity>:
 *           <scope name>:
 *             properties:
 *               <property name>:
 *                 validators:
 *                   <validator name>: <validator options>
 *                   <validator name>: <validator options>
 * `
 *
 * **Example**
 *
 * `
 * OpenAgenda:
 *   Application:
 *     Validation:
 *       ModelScopeValidator:
 *         OpenAgenda\Application\Framework\Model\Account:
 *           create:
 *             properties:
 *               username:
 *                 validators:
 *                   NotEmpty: []
 *                   EmailAddress: []
 *               firstName:
 *                 validators:
 *                   NotEmpty: []
 *                   StringLength:
 *                     minimum: 2
 *               password:
 *                 validators:
 *                   NotEmpty: []
 *                   StringLength:
 *                     minimum: 8
 *               passwordRepeat:
 *                 validators:
 *                   NotEmpty: []
 *                   StringLength:
 *                     minimum: 8
 *                   OpenAgenda\Application\Validation\Validator\SameValidator:
 *                     propertyValue: '$password'
 * `
 *
 * @package OpenAgenda\Application\Validation\Validator
 * @author Oliver Hader <oliver@typo3.org>
 */
class ModelScopeValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @var \OpenAgenda\Application\Service\ObjectService
	 * @Flow\Inject
	 */
	protected $objectService;

	/**
	 * @var \TYPO3\Flow\Validation\ValidatorResolver
	 * @Flow\Inject
	 */
	protected $validatorResolver;

	/**
	 * @var array
	 * @Flow\Inject(setting="Validation.ModelScopeValidator")
	 */
	protected $validatorSettings;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * Specifies whether this validator accepts empty values.
	 *
	 * If this is TRUE, the validators isValid() method is not called in case of an empty value
	 * Note: A value is considered empty if it is NULL or an empty string!
	 * By default all validators except for NotEmpty and the Composite Validators accept empty values
	 *
	 * @var boolean
	 */
	protected $acceptsEmptyValues = FALSE;

	/**
	 * Contains the supported options.
	 *
	 * @var array
	 */
	protected $supportedOptions = array(
		'scopeName' => array(NULL, 'Name of the scope', 'string', TRUE),
	);

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param mixed $value The entity to be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException if invalid validation options have been specified in the constructor
	 */
	protected function isValid($value) {
		$className = get_class($value);
		$scopeName = $this->options['scopeName'];

		if (empty($this->validatorSettings[$className])) {
			throw new \RuntimeException(
				'Class name "' . $className . '" is not configured',
				1416912059
			);
		}
		if (empty($this->validatorSettings[$className][$scopeName])) {
			throw new \RuntimeException(
				'Scope name "' . $scopeName . '" is not configured',
				1416912060
			);
		}

		$this->settings = $this->validatorSettings[$className][$scopeName];
		$this->validateProperties($value);
		$this->validateEntity($value);
	}

	/**
	 * Validates an entity.
	 *
	 * @param object $value The entity to be validated
	 * @return void
	 */
	protected function validateEntity($value) {
		if (empty($this->settings['entity']['validators'])) {
			return;
		}

		if (!empty($this->settings['entity']['factoryCallback'])) {
			$value = $this->objectService->executeStringCallback(
				$this->settings['entity']['factoryCallback'],
				$value
			);
		}

		$result = $this->result;
		if (!empty($this->settings['entity']['resultProperty'])) {
			$resultProperty = $this->settings['entity']['resultProperty'];
			$result = $result->forProperty($resultProperty);
		}

		$entityResult = $this->getConjunctionValidator($this->settings['entity']['validators'], $value)->validate($value);
		$result->merge($entityResult);
	}

	/**
	 * Validates properties for an entity.
	 *
	 * @param object $value The entity to be validated
	 * @return void
	 */
	protected function validateProperties($value) {
		if (empty($this->settings['properties'])) {
			return;
		}

		foreach ($this->settings['properties'] as $propertyName => $propertySettings) {
			if (empty($propertySettings['validators'])) {
				throw new \RuntimeException(
					'No validators configured for property "' . $propertyName . '"',
					1416912061
				);
			}

			$propertyValue = \TYPO3\Flow\Reflection\ObjectAccess::getPropertyPath($value, $propertyName);
			$propertyResult = $this->getConjunctionValidator($propertySettings['validators'], $value)->validate($propertyValue);

			$this->result->forProperty($propertyName)->merge($propertyResult);
		}
	}

	/**
	 * Gets a conjunction validator as defined in the settings.
	 * The resulting validator combines all particular sub-validators in one.
	 *
	 * @param array $validatorSettings Validator settings to be applied
	 * @param mixed $value The entity to be validated
	 * @return \TYPO3\Flow\Validation\Validator\ConjunctionValidator
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationConfigurationException
	 * @throws \TYPO3\Flow\Validation\Exception\NoSuchValidatorException
	 */
	protected function getConjunctionValidator(array $validatorSettings, $value) {
		/** @var \TYPO3\Flow\Validation\Validator\ConjunctionValidator $conjunctionValidator */
		$conjunctionValidator = $this->validatorResolver->createValidator('Conjunction');

		foreach ($validatorSettings as $validatorName => $validatorOptions) {
			$validatorOptions = $this->objectService->substituteStringVariables(
				$validatorOptions,
				$value
			);
			$conjunctionValidator->addValidator(
				$this->validatorResolver->createValidator($validatorName, $validatorOptions)
			);
		}

		return $conjunctionValidator;
	}

}