<?php
namespace OpenAgenda\Application\ViewHelpers\Exception;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class FallbackViewHelper
 *
 * ViewHelper that allows to catch Exceptions during the rendering process.
 *
 * **Example**
 *
 * `<oa:exception.fallback message="Something went wrong">... do something here</oa:exception.fallback>`
 *
 * @package OpenAgenda\Application\ViewHelpers\Exception
 * @author Oliver Hader <oliver@typo3.org>
 */
class FallbackViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Renders children and catches exceptions (if required).
	 *
	 * @param string|NULL $message Message to be show instead of the exception message
	 * @return string
	 */
	public function render($message = NULL) {
		try {
			$content = $this->renderChildren();
		} catch (\Exception $exception) {
			if ($message === NULL) {
				$message = $exception->getMessage();
			}
			$content = $message;
		}

		return $content;
	}

}