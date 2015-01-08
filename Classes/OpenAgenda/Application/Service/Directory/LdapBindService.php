<?php
namespace OpenAgenda\Application\Service\Directory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class LdapBindService
 *
 * Alternative LDAP bind service, used for simulating a user password
 * int the process of authentication in the TYPO3 Flow security layer.
 *
 * **Enabling simulation mode**
 *
 * *in Configuration/Settings.yaml or any other context specific
 * configuration file of the global TYPO3 Flow instance*
 *
 * `
 * TYPO3:
 *    Flow:
 *      security:
 *        authentication:
 *          providers:
 *            LdapProvider:
 *              providerOptions:
 *                className: 'OpenAgenda\Application\Service\Directory\LdapBindService
 *                simulation:
 *                  password: 'password'
 * `
 *
 * @package OpenAgenda\Application\Service\Directory
 * @author Oliver Hader <oliver@typo3.org>
 */
class LdapBindService extends \TYPO3\LDAP\Service\BindProvider\LDAPBind {

	/**
	 * Binds by $username and $password
	 *
	 * @param string $username Submitted username (not verified here)
	 * @param string $password Submitted password (compared against the simulation setting)
	 * @return void
	 * @throws \TYPO3\Flow\Error\Exception
	 */
	public function verifyCredentials($username, $password) {
		if (empty($this->options['simulation']['password']) || $password !== $this->options['simulation']['password']) {
			throw new \TYPO3\Flow\Error\Exception('Could not verify simulated credentials for dn: "' . $username . '"', 1327749076);
		}
	}

}