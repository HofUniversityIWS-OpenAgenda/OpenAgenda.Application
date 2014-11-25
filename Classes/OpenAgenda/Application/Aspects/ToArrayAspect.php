<?php
namespace OpenAgenda\Application\Aspects;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ArrayTypeConversionAspect
 *
 * @Flow\Introduce("class(OpenAgenda\Application\Domain\Model\.*)", interfaceName="OpenAgenda\Application\Aspects\ToArrayInterface")
 * @Flow\Aspect
 * @package OpenAgenda\Application\Service\TypeConversion
 * @author Oliver Hader <oliver@typo3.org>
 */
class ToArrayAspect {

	/**
	 * @var \OpenAgenda\Application\Service\ArrayService
	 * @Flow\Inject
	 */
	protected $arrayService;

	/**
	 * Around advice, implements the new method "newMethod" of the
	 * "NewInterface" interface
	 *
	 * @Flow\Around("method(.*->toArray())")
	 * @param  \TYPO3\Flow\AOP\JoinPointInterface $joinPoint The current join point
	 * @return array
	 */
	public function toArrayImplementation(\TYPO3\Flow\AOP\JoinPointInterface $joinPoint) {
		$source = $joinPoint->getProxy();

		if ($source instanceof \TYPO3\Flow\Persistence\QueryResultInterface) {
			$result = array();
			foreach ($source as $key => $value) {
				$result[] = $value->toArray();
			}
		} else {
			$result = $this->arrayService->convert($source);
		}

		return $result;
	}

}