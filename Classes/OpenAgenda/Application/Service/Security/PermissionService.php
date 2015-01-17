<?php
namespace OpenAgenda\Application\Service\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class PermissionService
 *
 * Basic class to handle permissions on this package level.
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Security
 */
class PermissionService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

	/**
	 * Currently not used.
	 *
	 * @return void
	 */
	public function isControllerAllowed() {
		
	}

	/**
	 * Currently not used.
	 *
	 * @return void
	 */
	public function isControllerActionAllowed() {

	}

	/**
	 * Determines whether the current Account entity has a managing role assigned.
	 * This role can either be defined as Administrator or MeetingManager.
	 *
	 * @return bool
	 * @throws \TYPO3\Flow\Security\Exception\NoSuchRoleException
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function hasManagingRole() {
		if (!$this->securityContext->isInitialized() && !$this->securityContext->canBeInitialized()) {
			return FALSE;
		}

		$account = $this->securityContext->getAccount();

		$meetingManagerRole = $this->policyService->getRole('OpenAgenda.Application:MeetingManager');
		$administratorRole = $this->policyService->getRole('OpenAgenda.Application:Administrator');

		return ($account->hasRole($meetingManagerRole) || $account->hasRole($administratorRole));
	}

	/**
	 * Determines whether the current Account entity has a minute-taker role assigned.
	 * Minute-takers exist only once per Meeting entity.
	 *
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting The objective meeting
	 * @return bool
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function hasMinuteTakerRole(\OpenAgenda\Application\Domain\Model\Meeting $meeting) {
		if (!$this->securityContext->isInitialized() && !$this->securityContext->canBeInitialized()) {
			return FALSE;
		}

		$account = $this->securityContext->getAccount();

		return ($meeting->getMinuteTaker() === $account->getParty());
	}

	/**
	 * Determines global permissions.
	 * The result is used in the frontend by AngularJS.
	 *
	 * @return array
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function determineGlobalPermissions() {
		return array(
			'meeting' => array(
				'create' => $this->hasManagingRole(),
			)
		);
	}

	/**
	 * Determines permissions for a particular meeting.
	 * The result is used in the frontend by AngularJS.
	 *
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting The objective meeting
	 * @return array
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function determineMeetingPermissions(\OpenAgenda\Application\Domain\Model\Meeting $meeting) {
		return array(
			'create' => $this->hasManagingRole(),
			'edit' => $this->hasManagingRole(),
			'delete' => $this->hasManagingRole(),
			'cancel' => $this->hasManagingRole(),
			'execute' => $this->hasManagingRole(),
			'minutes' => $this->hasMinuteTakerRole($meeting),
		);
	}

}