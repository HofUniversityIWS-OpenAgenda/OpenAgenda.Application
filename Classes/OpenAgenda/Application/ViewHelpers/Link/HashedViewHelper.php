<?php
namespace OpenAgenda\Application\ViewHelpers\Link;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class HashedViewHelper
 *
 * This view helper creates signed hashes over the given URL arguments.
 *
 * **Example**
 *
 * <code>
 * <oa:link.hashed action="confirm"
 *     controller="Authentication" package="OpenAgenda.Application"
 *     arguments="{account: account}" as="uri">
 *     {uri}
 * </oa:link.hashed>
 * </code>
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
	 * Renders an HTML anchor tag with all arguments being signed by a hash.
	 *
	 * @param string $action The name of the controller action to be called
	 * @param array $arguments The arguments to be used in the URI to be created
	 * @param NULL $controller The name of the controller to be called
	 * @param NULL $package The name of the package to be used
	 * @param bool $absolute Whether to create absolute URI
	 * @param bool $addTimestamp Whether to add a timestamp to the URI arguments
	 * @param string $as Provide generated URI for inner usage (default to "uri")
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