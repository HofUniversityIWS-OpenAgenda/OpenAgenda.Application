<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Invitation;

/**
 * Class InvitationController
 *
 * @package OpenAgenda\Application\Controller
 * @author Andreas Steiger <andreas.steiger@hof-university.de>
 */
class InvitationController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\InvitationRepository
	 */
	protected $invitationRepository;

	/**
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('value', $this->arrayService->flatten($this->invitationRepository->findByPerson()));
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Invitation $invitation
	 * @return void
	 */
	public function showAction(Invitation $invitation) {
		$this->view->assign('invitation', $invitation);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Invitation $newInvitation
	 * @return void
	 */
	public function createAction(Invitation $newInvitation) {
		$this->invitationRepository->add($newInvitation);
		$this->addFlashMessage('Created a new invitation.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Invitation $invitation
	 * @return void
	 */
	public function updateAction(Invitation $invitation) {
		$this->invitationRepository->update($invitation);
		$this->addFlashMessage('Updated the invitation.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Invitation $invitation
	 * @return void
	 */
	public function deleteAction(Invitation $invitation) {
		$this->invitationRepository->remove($invitation);
		$this->addFlashMessage('Deleted a invitation.');
		$this->redirect('index');
	}

	/**
	 * @param Invitation $invitation
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function acceptAction(Invitation $invitation) {
		$invitation->setStatus(Invitation::STATUS_COMMITTED);
		$this->invitationRepository->update($invitation);
		$this->historyService->invoke($invitation);
		$this->persistenceManager->persistAll();
		$this->redirect('index', 'Standard');
	}

	/**
	 * @param Invitation $invitation
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function declineAction(Invitation $invitation) {
		$invitation->setStatus(Invitation::STATUS_COMMITTED);
		$this->invitationRepository->update($invitation);
		$this->historyService->invoke($invitation);
		$this->persistenceManager->persistAll();
		$this->redirect('index', 'Standard');
	}

}