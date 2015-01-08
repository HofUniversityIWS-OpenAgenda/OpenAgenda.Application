<?php
namespace OpenAgenda\Application\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Interface SortableInterface
 *
 * @package OpenAgenda\Application\Domain\Model
 * @author Oliver Hader <oliver@typo3.org>
 */
interface SortableInterface {

	/**
	 * @param int $sorting
	 * @return void
	 */
	public function setSorting($sorting);

	/**
	 * @return int
	 */
	public function getSorting();

}