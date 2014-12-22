<?php
namespace OpenAgenda\Application\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Model Person
 *
 * @package OpenAgenda\Application\Domain\Model
 * @author Oliver Hader <oliver@typo3.org>
 * @Flow\Entity
 * @ORM\Table(name="oa_person")
 */
class Person extends \TYPO3\Party\Domain\Model\Person {

	/**
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $phoneNumber;

	/**
	 * @var Preference
	 * @ORM\OneToOne(cascade={"all"}, orphanRemoval=true)
	 */
	protected $preference;

	/**
	 * @return string
	 */
	public function getPhoneNumber() {
		return $this->phoneNumber;
	}

	/**
	 * @param string $phoneNumber
	 */
	public function setPhoneNumber($phoneNumber) {
		$this->phoneNumber = $phoneNumber;
	}

	/**
	 * @return Preference
	 */
	public function getPreference() {
		return $this->preference;
	}

	/**
	 * @param Preference $preference
	 */
	public function setPreference(Preference $preference) {
		$this->preference = $preference;
	}

}