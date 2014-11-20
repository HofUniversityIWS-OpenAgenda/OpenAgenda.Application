<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\ProtocolItem;

class ProtocolItemController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\ProtocolItemRepository
	 */
	protected $protocolItemRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('protocolItems', $this->protocolItemRepository->findAll());
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @return void
	 */
	public function showAction(protocolItem $protocolItem) {
		$this->view->assign('protocolItem', $protocolItem);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $newprotocolItem
	 * @return void
	 */
	public function createAction(protocolItem $newprotocolItem) {
		$this->protocolItemRepository->add($newprotocolItem);
		$this->addFlashMessage('Created a new protocol item.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @return void
	 */
	public function editAction(protocolItem $protocolItem) {
		$this->view->assign('protocolItem', $protocolItem);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @return void
	 */
	public function updateAction(protocolItem $protocolItem) {
		$this->protocolItemRepository->update($protocolItem);
		$this->addFlashMessage('Updated the protocol item.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\ProtocolItem $protocolItem
	 * @return void
	 */
	public function deleteAction(protocolItem $protocolItem) {
		$this->protocolItemRepository->remove($protocolItem);
		$this->addFlashMessage('Deleted a protocol item.');
		$this->redirect('index');
	}

}