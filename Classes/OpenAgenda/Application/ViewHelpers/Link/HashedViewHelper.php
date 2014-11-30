<?php
namespace OpenAgenda\Application\ViewHelpers\Link;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class SubjectViewHelper
 *
 * @package OpenAgenda\Application\ViewHelpers\Message
 * @author Oliver Hader <oliver@typo3.org>
 */
class HashedViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Service\Security\ArgumentService
	 */
	protected $argumentService;

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * Initialize arguments
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('name', 'string', 'Specifies the name of an anchor');
		$this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document');
		$this->registerTagAttribute('rev', 'string', 'Specifies the relationship between the linked document and the current document');
		$this->registerTagAttribute('target', 'string', 'Specifies where to open the linked document');
	}

	/**
	 * @param string $action
	 * @param array $arguments
	 * @param NULL $controller
	 * @param NULL $package
	 * @param bool $absolute
	 * @param bool $addTimestamp
	 * @param string $as
	 * @return string
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	public function render($action, $arguments = array(), $controller = NULL, $package = NULL, $absolute = TRUE, $addTimestamp = FALSE, $as = 'uri') {
		$arguments = $this->argumentService->hash($arguments);
		$uriBuilder = $this->controllerContext->getUriBuilder()->reset()->setCreateAbsoluteUri($absolute);

		try {
			$uri = $uriBuilder->uriFor($action, $arguments, $controller, $package);
		} catch (\Exception $exception) {
			throw new \TYPO3\Fluid\Core\ViewHelper\Exception($exception->getMessage(), $exception->getCode(), $exception);
		}

		$this->templateVariableContainer->add($as, $uri);
		$content = $this->renderChildren();
		$this->templateVariableContainer->remove($as);

		$this->tag->addAttribute('href', $uri);
		$this->tag->setContent($content);
		$this->tag->forceClosingTag(TRUE);

		return $this->tag->render();
	}

}