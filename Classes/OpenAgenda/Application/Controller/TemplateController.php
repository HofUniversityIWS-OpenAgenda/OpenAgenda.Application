<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

/**
 * Class TemplateController
 *
 * This controller is serving AngularJS to retrieve particular templates.
 * Those templates are passed through the TYPO3 Fluid rendering engine
 * and thus contain parts that have been processed and substituted on
 * the server-side already.
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
	 * Gets a processed template.
	 *
	 * @param string $controller The controller name (e.g. "Meeting")
	 * @param string $action The action name (e.g. "list")
	 * @param string $format The format name (e.g. "html")
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