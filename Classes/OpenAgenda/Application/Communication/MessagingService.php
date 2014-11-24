<?php
namespace OpenAgenda\Application\Communication;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;


class MessagingService {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	 */
	protected $messageRepository;
	/**
	* @var
	*/
	protected $documentRenderingService;


}