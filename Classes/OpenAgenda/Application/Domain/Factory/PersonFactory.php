<?php
namespace OpenAgenda\Application\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Party\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;
use TYPO3\Party\Domain\Model\ElectronicAddress;

/**
 * Class PersonFactory
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Export
 */
class PersonFactory {

	/**
	 * @param string $email
	 * @return Person
	 */
	public function createAnonymousPersonWithElectronicAddress($email) {
		$electronicAddress = new ElectronicAddress();
		$electronicAddress->setIdentifier($email);
		$electronicAddress->setType(ElectronicAddress::TYPE_EMAIL);

		$name = new \TYPO3\Party\Domain\Model\PersonName();
		// @todo Enrich with real data once registration form requires that
		$name->setFirstName('Anonymous');
		$name->setLastName('User');

		$person = new Person();
		$person->setName($name);
		$person->setPrimaryElectronicAddress($electronicAddress);

		return $person;
	}

}