<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

/**
 * Class TemplateController
 *
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
class TemplateController extends ActionController {

	/**
	 * @var \TYPO3\Fluid\View\TemplateView
	 */
	protected $view;

	/**
	 * @param string $controller
	 * @param string $action
	 * @param string $format
	 */
	public function getAction($controller, $action, $format) {
		$templatePathAndFilenamePattern = $this->view->getOption('templatePathAndFilenamePattern');

		$templatePathAndFilenamePattern = str_replace(
			array('@controller', '@action', '@format'),
			array(ucfirst($controller), ucfirst($action), $format),
			$templatePathAndFilenamePattern
		);

		$this->view->setOption('templatePathAndFilenamePattern', $templatePathAndFilenamePattern);
	}

}