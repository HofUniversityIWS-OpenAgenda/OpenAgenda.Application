<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use OpenAgenda\Application\Domain\Model\AgendaItem;
use OpenAgenda\Application\Domain\Model\Meeting;
use TYPO3\Flow\Annotations as Flow;

class PersonController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * Determines all active persons (e.g. for invitation).
	 *
	 * @return void
	 */
	public function indexAction() {
		$persons = array();

		/** @var \TYPO3\Party\Domain\Model\Person $person */
		foreach ($this->personRepository->findAll() as $person) {
			if (!$this->hasValidAccount($person)) {
				continue;
			}
			$persons[] = $this->arrayService->prepare($person);
		}

		$this->view->assign('value', $persons);
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function showAction(\TYPO3\Party\Domain\Model\Person $person) {
		$this->view->assign('value', $this->arrayService->prepare($person));
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @return bool
	 */
	protected function hasValidAccount(\TYPO3\Party\Domain\Model\Person $person) {
		/** @var \TYPO3\Flow\Security\Account $account */
		foreach ($person->getAccounts() as $account) {
			if ($account->getExpirationDate() === NULL || (int)$account->getExpirationDate()->format('U') !== 0) {
				return TRUE;
			}
		}

		return FALSE;
	}

}