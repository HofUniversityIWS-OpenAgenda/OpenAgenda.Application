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
 * @package OpenAgenda\Application\Service\TypeConversion
 * @author Oliver Hader <oliver@typo3.org>
 */
interface ToArrayInterface {

	/**
	 * @return array
	 */
	public function toArray();

}