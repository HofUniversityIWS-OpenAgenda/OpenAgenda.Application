<?php
namespace OpenAgenda\Application\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

/**
 * Class MessageView
 * @package OpenAgenda\Application\View
 * @author Oliver Hader <oliver@typo3.org>
 */
class MessageView extends \TYPO3\Fluid\View\StandaloneView {

	/**
	 * @return string
	 */
	public function getSubject() {
		$templateVariableContainer = $this->baseRenderingContext->getTemplateVariableContainer();
		$subjectIdentifier = \OpenAgenda\Application\ViewHelpers\Message\SubjectViewHelper::getClassName();

		try {
			$subject = (string)$templateVariableContainer->get($subjectIdentifier);
		} catch (\TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException $exception) {
			$subject = '';
		}

		return $subject;
	}

}