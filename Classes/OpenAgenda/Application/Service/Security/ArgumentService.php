<?php
namespace OpenAgenda\Application\Service\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class ArgumentService
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Security
 * @author Oliver Hader <oliver@typo3.org>
 */
class ArgumentService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Utility\Now
	 */
	protected $now;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @param array $arguments
	 * @param bool $addTimestamp
	 * @return array
	 */
	public function hash(array $arguments, $addTimestamp = TRUE) {
		if ($addTimestamp) {
			$arguments['_time'] = $this->now->format('U');
		}

		$arguments['_hash'] = $this->hashService->generateHmac($this->getPayload($arguments));
		return $arguments;
	}

	/**
	 * @param array $arguments
	 * @param int $lifetime
	 * @throws \TYPO3\Flow\Security\Exception
	 */
	public function validate(array $arguments, $lifetime = 43200) {
		if (empty($arguments['_hash'])) {
			throw new \TYPO3\Flow\Security\Exception('No hash value given', 1417377521);
		}
		if (empty($arguments['_time']) && $lifetime > 0) {
			throw new \TYPO3\Flow\Security\Exception('No timestamp value given', 1417377522);
		}

		$expectedHash = $this->hashService->generateHmac($this->getPayload($arguments));

		if ($expectedHash !== $arguments['_hash']) {
			throw new \TYPO3\Flow\Security\Exception('Invalid hash value', 1417377523);
		}
		if ($this->now->format('U') - $lifetime > $arguments['_time']) {
			throw new \TYPO3\Flow\Security\Exception('Lifetime has expired', 1417377524);
		}
	}

	/**
	 * @param array $arguments
	 * @return string
	 */
	protected function getPayload(array $arguments) {
		if (isset($arguments['_hash'])) {
			unset($arguments['_hash']);
		}

		ksort($arguments);
		$arguments = array_map(
			array($this, 'flatten'),
			$this->persistenceManager->convertObjectsToIdentityArrays($arguments)
		);

		return implode('::', array_keys($arguments)) . '//' . implode('::', array_values($arguments));
	}

	/**
	 * @param string|array $value
	 * @return string
	 */
	protected function flatten($value) {
		if (!is_array($value)) {
			return $value;
		}

		foreach ($value as &$item) {
			if (is_array($item)) {
				$item = $this->flatten($item);
			}
		}

		return implode('', $value);
	}

}