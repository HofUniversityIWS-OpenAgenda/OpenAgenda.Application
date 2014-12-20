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
 * @Target({"PROPERTY","CLASS"})
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
	 * @var string
	 */
	protected $transientName;

	/**
	 * @var array
	 */
	protected $scopeNames = array();

	/**
	 * @var array
	 */
	protected $denyScopeNames = array();

	/**
	 * @param array $values
	 */
	public function __construct(array $values) {
		if (isset($values['useIdentifier'])) {
			$this->useIdentifier = TRUE;
		} elseif (!empty($values['callback'])) {
			$this->callback = $values['callback'];
		}

		if (isset($values['transientName'])) {
			$this->transientName = $values['transientName'];
		}

		if (isset($values['scope'])) {
			$scopeNames = NULL;

			if (is_array($values['scope'])) {
				$scopeNames = $values['scope'];
			} elseif (is_string($values['scope'])) {
				$scopeNames = array_map(
					'trim',
					explode(',', $values['scope'])
				);
			}

			if ($scopeNames !== NULL) {
				foreach ($scopeNames as $scopeName) {
					if ($scopeName{0} === '!') {
						$this->denyScopeNames[] = substr($scopeName, 1);
					} else {
						$this->scopeNames[] = $scopeName;
					}
				}
			}
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

	public function getTransientName() {
		return $this->transientName;
	}

	/**
	 * @return array
	 */
	public function getScopeNames() {
		return $this->scopeNames;
	}

	/**
	 * @return array
	 */
	public function getDenyScopeNames() {
		return $this->denyScopeNames;
	}

}