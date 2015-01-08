<?php
namespace OpenAgenda\Application\Service\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class ArgumentService
 *
 * This service is used to check and extend URL arguments
 * to either create signing hash over all arguments and
 * to additionally add a lifetime for the resulting link.
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
	 * Creates a hash over all arguments.
	 *
	 * @param array $arguments The arguments to be extended by a hash
	 * @param bool $addTimestamp Optionally add a timestamp for lifetime checks
	 * @return array The extended arguments
	 */
	public function hash(array $arguments, $addTimestamp = TRUE) {
		if ($addTimestamp) {
			$arguments['_time'] = $this->now->format('U');
		}

		$arguments['_hash'] = $this->hashService->generateHmac($this->getPayload($arguments));
		return $arguments;
	}

	/**
	 * Validates arguments against a given hash and lifetime.
	 *
	 * @param array $arguments The arguments to be validated.
	 * @param int $lifetime Optional maximum lifetime (if "_time" argument is available)
	 * @return void
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
	 * Gets the payload from arguments that can be signed by a hash.
	 * Prior to sign (= serialize and hmac) an object, its complexity
	 * and self-references need to be reduced and resolved.
	 *
	 * @param array $arguments The arguments
	 * @return string Payload of arguments that can be signed
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
	 * Flattens arrays and reduces complexity.
	 *
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