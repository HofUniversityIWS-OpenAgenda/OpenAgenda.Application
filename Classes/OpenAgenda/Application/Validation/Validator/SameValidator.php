<?php
namespace OpenAgenda\Application\Validation\Validator;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class SameValidator
 * @package OpenAgenda\Application\Validation\Validator
 * @author Oliver Hader <oliver@typo3.org>
 */
class SameValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

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
		'propertyValue' => array(NULL, 'Name of the property to compare against', 'mixed', TRUE),
	);

	/**
	 * Check if $value is valid. If it is not valid, needs to add an error
	 * to Result.
	 *
	 * @param mixed $value
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidValidationOptionsException if invalid validation options have been specified in the constructor
	 */
	protected function isValid($value) {
		if ($this->options['propertyValue'] === NULL) {
			throw new \RuntimeException('Option "propertyValue" is not defined', 1416916267);
		}

		if ($this->options['propertyValue'] !== $value) {
			$this->addError('Values are not the same', 1416916268);
		}
	}

}