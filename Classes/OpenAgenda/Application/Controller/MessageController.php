<?php
namespace OpenAgenda\Application\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;
use OpenAgenda\Application\Domain\Model\Message;

class MessageController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \OpenAgenda\Application\Domain\Repository\MessageRepository
	 */
	protected $messageRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('messages', $this->messageRepository->findAll());
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Message $message
	 * @return void
	 */
	public function showAction(Message $message) {
		$this->view->assign('message', $message);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Message $newMessage
	 * @return void
	 */
	public function createAction(Message $newMessage) {
		$this->messageRepository->add($newMessage);
		$this->addFlashMessage('Created a new message.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Message $message
	 * @return void
	 */
	public function editAction(Message $message) {
		$this->view->assign('message', $message);
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Message $message
	 * @return void
	 */
	public function updateAction(Message $message) {
		$this->messageRepository->update($message);
		$this->addFlashMessage('Updated the message.');
		$this->redirect('index');
	}

	/**
	 * @param \OpenAgenda\Application\Domain\Model\Message $message
	 * @return void
	 */
	public function deleteAction(Message $message) {
		$this->messageRepository->remove($message);
		$this->addFlashMessage('Deleted a message.');
		$this->redirect('index');
	}

}