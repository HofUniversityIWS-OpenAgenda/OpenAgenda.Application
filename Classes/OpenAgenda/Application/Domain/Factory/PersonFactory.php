<?php
namespace OpenAgenda\Application\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Person;
use TYPO3\Party\Domain\Model\PersonName;
use TYPO3\Party\Domain\Model\ElectronicAddress;

/**
 * Class PersonFactory
 *
 * This factory provides Person entities.
 *
 * @Flow\Scope("singleton")
 * @package OpenAgenda\Application\Service\Export
 * @author Oliver Hader <oliver@typo3.org>
 */
class PersonFactory {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\ElectronicAddressRepository
	 */
	protected $electronicAddressRepository;

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * Creates an anonymous person (no names) with one mail address.
	 *
	 * @param string $email Mail address to be assigned
	 * @return Person
	 * @deprecated Use createPerson() instead
	 */
	public function createAnonymousPersonWithElectronicAddress($email) {
		$electronicAddress = new ElectronicAddress();
		$electronicAddress->setIdentifier($email);
		$electronicAddress->setType(ElectronicAddress::TYPE_EMAIL);

		$name = new PersonName();
		// @todo Enrich with real data once registration form requires that
		$name->setFirstName('Anonymous');
		$name->setLastName('User');

		$person = new Person();
		$person->setName($name);
		$person->setPrimaryElectronicAddress($electronicAddress);

		$this->electronicAddressRepository->add($electronicAddress);
		$this->personRepository->add($person);

		return $person;
	}

	/**
	 * Creates a person entity with names and mail address.
	 *
	 * @param string $email Mail address to be assigned.
	 * @param string $firstName First name to be used
	 * @param string $lastName Last name to be used
	 * @return Person
	 */
	public function createPerson($email, $firstName, $lastName) {
		$electronicAddress = new ElectronicAddress();
		$electronicAddress->setIdentifier($email);
		$electronicAddress->setType(ElectronicAddress::TYPE_EMAIL);

		$name = new PersonName();
		$name->setFirstName($firstName);
		$name->setLastName($lastName);

		$person = new Person();
		$person->setName($name);
		$person->setPrimaryElectronicAddress($electronicAddress);

		$this->electronicAddressRepository->add($electronicAddress);
		$this->personRepository->add($person);

		return $person;
	}

	/**
	 * Updates person entity and take care of migration path.
	 *
	 * @param \TYPO3\Party\Domain\Model\Person $person
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function updatePerson(\TYPO3\Party\Domain\Model\Person $person) {
		if ($person instanceof Person) {
			if ($person->getPreference() === NULL) {
				$preference = new \OpenAgenda\Application\Domain\Model\Preference();
				$preference->setModificationDate(new \DateTime());
				$person->setPreference($preference);
				$this->personRepository->update($person);
			}
		}
	}

}