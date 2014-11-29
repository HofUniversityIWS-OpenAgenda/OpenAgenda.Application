<?php
namespace OpenAgenda\Application\Service\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;


class PermissionService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @return void
	 */
	public function isControllerAllowed() {
		
	}

	/**
	 * @return void
	 */
	public function isControllerActionAllowed() {

	}

}