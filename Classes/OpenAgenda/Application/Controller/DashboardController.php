<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class DashboardController
 *
 * @package OpenAgenda\Application\Controller
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class DashboardController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MeetingRepository
	 */
	protected $meetingRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	 */
	protected $messageRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\InvitationRepository
	 */
	protected $invitationRepository;

	/**
	 * Provides basic information for AngularJS, such as
	 *
	 * + information on currently logged in user (person)
	 * + general permission information for the user
	 * + open/unanswered invitations
	 * + all allowed meetings
	 * + all allowed tasks
	 *
	 * @return void
	 * @author Oliver Hader <oliver@typo3.org>
	 */
	public function indexAction() {
		$account = $this->securityContext->getAccount();

		/** @var \OpenAgenda\Application\Domain\Model\Person $person */
		$person = $account->getParty();

		$openInvitations = array();
		foreach ($this->invitationRepository->findOpen() as $invitation) {
			$openInvitations[] = array(
				'__identity' => $this->persistenceManager->getIdentifierByObject($invitation),
				'creationDate' => $invitation->getCreationDate()->format('c'),
				'meeting' => array(
					'__identity' => $this->persistenceManager->getIdentifierByObject($invitation->getMeeting()),
					'title' => $invitation->getMeeting()->getTitle(),
				)
			);
		}

		$value = array(
			'person' => $this->arrayService->prepare($person),
			'meetings' => $this->arrayService->flatten($this->meetingRepository->findAllowed(), 'list'),
			'permissions' => $this->permissionService->determineGlobalPermissions(),
			'tasks' => $this->arrayService->flatten($this->taskRepository->findAllowed(), 'list'),
			'openInvitations' => $openInvitations,
		);

		$this->view->assign('value', $value);
	}

}