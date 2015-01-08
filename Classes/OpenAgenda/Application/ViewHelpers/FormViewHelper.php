<?php
namespace OpenAgenda\Application\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

/**
 * Class FormViewHelper
 *
 * Extends the basic FormViewHelper and allows to use additional arguments,
 * such as a role attribute.
 *
 * @package OpenAgenda\Application\ViewHelpers
 * @author Oliver Hader <oliver@typo3.org>
 */
class FormViewHelper extends \TYPO3\Fluid\ViewHelpers\FormViewHelper {

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('role', 'string', 'Role type of a given HTML element');
		parent::initializeArguments();
	}

	/**
	 * Render the form.
	 *
	 * @param string $action target action
	 * @param array $arguments additional arguments
	 * @param string $controller name of target controller
	 * @param string $package name of target package
	 * @param string $subpackage name of target subpackage
	 * @param mixed $object object to use for the form. Use in conjunction with the "property" attribute on the sub tags
	 * @param string $section The anchor to be added to the action URI (only active if $actionUri is not set)
	 * @param string $format The requested format (e.g. ".html") of the target page (only active if $actionUri is not set)
	 * @param array $additionalParams additional action URI query parameters that won't be prefixed like $arguments (overrule $arguments) (only active if $actionUri is not set)
	 * @param boolean $absolute If set, an absolute action URI is rendered (only active if $actionUri is not set)
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the action URI (only active if $actionUri is not set)
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the action URI. Only active if $addQueryString = TRUE and $actionUri is not set
	 * @param string $fieldNamePrefix Prefix that will be added to all field names within this form
	 * @param string $actionUri can be used to overwrite the "action" attribute of the form tag
	 * @param string $objectName name of the object that is bound to this form. If this argument is not specified, the name attribute of this form is used to determine the FormObjectName
	 * @param boolean $useParentRequest If set, the parent Request will be used instead ob the current one
	 * @return string rendered form
	 */
	public function render($action = NULL, array $arguments = array(), $controller = NULL, $package = NULL, $subpackage = NULL, $object = NULL, $section = '', $format = '', array $additionalParams = array(), $absolute = FALSE, $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $fieldNamePrefix = NULL, $actionUri = NULL, $objectName = NULL, $useParentRequest = FALSE) {
		if (isset($this->arguments['role'])) {
			$this->tag->addAttribute('role', $this->arguments['role']);
		}

		return parent::render($action, $arguments, $controller, $package, $subpackage, $object, $section, $format, $additionalParams, $absolute, $addQueryString, $argumentsToBeExcludedFromQueryString, $fieldNamePrefix, $actionUri, $objectName, $useParentRequest);
	}

}