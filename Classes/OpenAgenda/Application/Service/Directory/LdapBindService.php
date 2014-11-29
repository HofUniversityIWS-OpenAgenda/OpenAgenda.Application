<?php
namespace OpenAgenda\Application\Service\Directory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class LdapBindService
 * @package OpenAgenda\Application\Service\Directory
 * @author Oliver Hader <oliver@typo3.org>
 */
class LdapBindService extends \TYPO3\LDAP\Service\BindProvider\LDAPBind {

	/**
	 * Bind by $username and $password
	 *
	 * @param string $username
	 * @param string $password
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function verifyCredentials($username, $password) {
		if (empty($this->options['simulation']['password']) || $password !== $this->options['simulation']['password']) {
			throw new \TYPO3\Flow\Error\Exception('Could not verify simulated credentials for dn: "' . $username . '"', 1327749076);
		}
	}

}