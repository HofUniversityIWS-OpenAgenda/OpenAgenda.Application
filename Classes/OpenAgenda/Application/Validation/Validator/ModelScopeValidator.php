<?php
namespace OpenAgenda\Application\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class ModelScopeValidator
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
	 * @param mixed $value
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
	 * @param object $value
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
	 * @param object $value
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

			$propertyValue = \TYPO3\Flow\Reflection\ObjectAccess::getProperty($value, $propertyName);
			$propertyResult = $this->getConjunctionValidator($propertySettings['validators'], $value)->validate($propertyValue);

			$this->result->forProperty($propertyName)->merge($propertyResult);
		}
	}

	/**
	 * @param array $validatorSettings
	 * @param mixed $value
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