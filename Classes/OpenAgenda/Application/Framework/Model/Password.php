<?php
namespace OpenAgenda\Application\Framework\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class Password
 *
 * Non persistable object handling password information.
 *
 * @package OpenAgenda\Application\Framework\Model
 * @author Oliver Hader <oliver@typo3.org>
 */
class Password {

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $passwordRepeat;

	/**
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @return string
	 */
	public function getPasswordRepeat() {
		return $this->passwordRepeat;
	}

	/**
	 * @param string $passwordRepeat
	 */
	public function setPasswordRepeat($passwordRepeat) {
		$this->passwordRepeat = $passwordRepeat;
	}

}