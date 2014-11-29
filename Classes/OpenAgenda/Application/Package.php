<?php
namespace OpenAgenda\Application;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\Package as BasePackage;

/**
 * Class Package
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application
 * @author Oliver Hader <oliver@typo3.org>
 */
class Package extends BasePackage {

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();

		$dispatcher->connect(
			'TYPO3\\LDAP\\Security\\Authentication\\Provider\\LDAPProvider', 'createAccount',
			'OpenAgenda\\Application\\Service\\Directory\\SynchronizationService', 'create'
		);
		$dispatcher->connect(
			'TYPO3\\LDAP\\Security\\Authentication\\Provider\\LDAPProvider', 'updateAccount',
			'OpenAgenda\\Application\\Service\\Directory\\SynchronizationService', 'update'
		);
	}

}