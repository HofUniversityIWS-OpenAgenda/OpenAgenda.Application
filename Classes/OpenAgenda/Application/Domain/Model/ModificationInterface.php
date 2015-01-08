<?php
namespace OpenAgenda\Application\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Interface ModificationInterface
 *
 * @package OpenAgenda\Application\Domain\Model
 * @author Oliver Hader <oliver@typo3.org>
 */
interface ModificationInterface {

	/**
	 * @param \DateTime $modificationDate
	 * @return void
	 */
	public function setModificationDate(\DateTime $modificationDate);

}