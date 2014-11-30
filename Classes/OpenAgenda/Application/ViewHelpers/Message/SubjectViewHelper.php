<?php
namespace OpenAgenda\Application\ViewHelpers\Message;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class SubjectViewHelper
 * @package OpenAgenda\Application\ViewHelpers\Message
 * @author Oliver Hader <oliver@typo3.org>
 */
class SubjectViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @return string
	 */
	static public function getClassName() {
		return __CLASS__;
	}

	/**
	 * @param string|NULL $subject
	 * @return void
	 */
	public function render($subject = NULL) {
		if ($subject === NULL) {
			$subject = $this->renderChildren();
		}

		$this->templateVariableContainer->add(static::getClassName(), $subject);
	}

}