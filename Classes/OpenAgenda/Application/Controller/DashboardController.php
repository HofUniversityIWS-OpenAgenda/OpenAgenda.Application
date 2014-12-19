<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class DashboardController extends AbstractController {

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

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
	 * @return void
	 */
	public function indexAction() {
		$account = $this->securityContext->getAccount();

		/** @var \TYPO3\Party\Domain\Model\Person $person */
		$person = $account->getParty();

		$value = array(
			'person' => $this->arrayService->prepare($person),
			'meetings' => $this->arrayService->flatten($this->meetingRepository->findAllowed(), 'list'),
			'permissions' => $this->permissionService->determineGlobalPermissions(),
			'tasks' => $this->arrayService->flatten($this->taskRepository->findAllowed(), 'list'),
		);

		$this->view->assign('value', $value);
	}

	/**
	 * Determines all active persons (e.g. for invitation).
	 *
	 * @return void
	 */
	public function personsAction() {
		$persons = array();

		/** @var \TYPO3\Flow\Security\Account $account */
		foreach ($this->accountRepository->findAll() as $account) {
			if ($account->getExpirationDate() !== NULL && (int)$account->getExpirationDate()->format('U') === 0) {
				continue;
			}
			$persons[] = $this->arrayService->prepare($account->getParty());
		}

		$this->view->assign('value', $persons);
	}

}