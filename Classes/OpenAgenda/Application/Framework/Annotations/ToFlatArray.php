<?php
namespace OpenAgenda\Application\Framework\Annotations;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * Class ToFlatArray
 *
 * @Annotation
 * @Target("PROPERTY")
 * @package OpenAgenda\Application\Service\TypeConversion
 * @author Oliver Hader <oliver@typo3.org>
 */
final class ToFlatArray {

	/**
	 * @var string
	 */
	protected $callback;

	/**
	 * @var bool
	 */
	protected $useIdentifier = FALSE;

	/**
	 * @param array $values
	 */
	public function __construct(array $values) {
		if (isset($values['useIdentifier'])) {
			$this->useIdentifier = TRUE;
		} elseif (!empty($values['callback'])) {
			$this->callback = $values['callback'];
		}
	}

	/**
	 * @return string
	 */
	public function getCallback() {
		return $this->callback;
	}

	/**
	 * @return bool
	 */
	public function getUseIdentifier() {
		return $this->useIdentifier;
	}

}