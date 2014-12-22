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
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

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

	/**
	 * @return bool
	 * @throws \TYPO3\Flow\Security\Exception\NoSuchRoleException
	 */
	public function hasManagingRole() {
		$account = $this->securityContext->getAccount();

		$meetingManagerRole = $this->policyService->getRole('OpenAgenda.Application:MeetingManager');
		$administratorRole = $this->policyService->getRole('OpenAgenda.Application:Administrator');

		return ($account->hasRole($meetingManagerRole) || $account->hasRole($administratorRole));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return bool
	 */
	public function hasMinuteTakerRole(\OpenAgenda\Application\Domain\Model\Meeting $meeting) {
		$account = $this->securityContext->getAccount();

		return ($meeting->getMinuteTaker() === $account->getParty());
	}

	/**
	 * @return array
	 */
	public function determineGlobalPermissions() {
		return array(
			'meeting' => array(
				'create' => $this->hasManagingRole(),
			)
		);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Meeting $meeting
	 * @return array
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