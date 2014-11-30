<?php
namespace OpenAgenda\Application\ViewHelpers\Exception;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class FallbackViewHelper
 * @package OpenAgenda\Application\ViewHelpers\Exception
 * @author Oliver Hader <oliver@typo3.org>
 */
class FallbackViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param string|NULL $message
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