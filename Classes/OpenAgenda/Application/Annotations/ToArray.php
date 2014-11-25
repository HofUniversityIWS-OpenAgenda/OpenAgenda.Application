<?php
namespace OpenAgenda\Application\Annotations;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class Json
 *
 * @Annotation
 * @Target("PROPERTY")
 * @package OpenAgenda\Application\Service\TypeConversion
 * @author Oliver Hader <oliver@typo3.org>
 */
final class ToArray {

	/**
	 * @var string
	 */
	protected $callback;

	/**
	 * @param array $values
	 */
	public function __construct(array $values) {
		if (isset($values['callback'])) {
			$this->callback = $values['callback'];
		}
	}

	/**
	 * @return string
	 */
	public function getCallback() {
		return $this->callback;
	}

}