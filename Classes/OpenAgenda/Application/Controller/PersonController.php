<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use OpenAgenda\Application\Domain\Model\AgendaItem;
use OpenAgenda\Application\Domain\Model\Meeting;
use TYPO3\Flow\Annotations as Flow;

/**
 * Class PersonController
 *
 * @package OpenAgenda\Application\Controller
 * @author Oliver Hader <oliver@typo3.org>
 */
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

		/** @var \OpenAgenda\Application\Domain\Model\Person $person */
		foreach ($this->personRepository->findAll() as $person) {
			if (!$this->hasValidAccount($person)) {
				continue;
			}
			$persons[] = $this->arrayService->prepare($person);
		}

		$this->view->assign('value', $persons);
	}

	/**
	 * Shows the public details of a given Person entity.
	 *
	 * @param \OpenAgenda\Application\Domain\Model\Person $person
	 * @return void
	 */
	public function showAction(\OpenAgenda\Application\Domain\Model\Person $person) {
		$this->view->assign('value', $this->arrayService->prepare($person));
	}

	/**
	 * Determines whether a Person entity has a valid and non-expired account.
	 *
	 * @param \OpenAgenda\Application\Domain\Model\Person $person
	 * @return bool
	 */
	protected function hasValidAccount(\OpenAgenda\Application\Domain\Model\Person $person) {
		/** @var \TYPO3\Flow\Security\Account $account */
		foreach ($person->getAccounts() as $account) {
			if ($account->getExpirationDate() === NULL || (int)$account->getExpirationDate()->format('U') !== 0) {
				return TRUE;
			}
		}

		return FALSE;
	}

}