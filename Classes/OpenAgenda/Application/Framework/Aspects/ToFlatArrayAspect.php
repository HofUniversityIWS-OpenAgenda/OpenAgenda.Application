<?php
namespace OpenAgenda\Application\Framework\Aspects;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ArrayTypeConversionAspect
 *
 * @Flow\Introduce("class(OpenAgenda\Application\Domain\Model\.*) || within(Iterator)", interfaceName="OpenAgenda\Application\Framework\Aspects\ToFlatArrayInterface")
 * @Flow\Aspect
 * @package OpenAgenda\Application\Service\TypeConversion
 * @author Oliver Hader <oliver@typo3.org>
 */
class ToFlatArrayAspect {

	/**
	 * @var \OpenAgenda\Application\Service\ArrayService
	 * @Flow\Inject
	 */
	protected $arrayService;

	/**
	 * Around advice, implements the new method "newMethod" of the
	 * "NewInterface" interface
	 *
	 * @Flow\Around("method(.*->toFlatArray())")
	 * @param \TYPO3\Flow\AOP\JoinPointInterface $joinPoint The current join point
	 * @return array
	 */
	public function toFlatArrayImplementation(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
		$source = $joinPoint->getProxy();
		$scopeName = $joinPoint->getMethodArgument('scopeName');
		return $this->arrayService->flatten($source, $scopeName);
	}

}