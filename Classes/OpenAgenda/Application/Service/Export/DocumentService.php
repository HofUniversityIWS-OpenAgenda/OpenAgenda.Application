<?php
namespace OpenAgenda\Application\Service\Export;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;

class DocumentService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @var array
	 * @Flow\Inject(setting="Export.Document")
	 */
	protected $documentSettings;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @param Meeting $meeting
	 * @return \TYPO3\Flow\Resource\Resource
	 */
	public function exportAgenda(Meeting $meeting) {
		$template = $this->substituteSettings($this->documentSettings['scopes']['agenda']['templateFile']);
		$source = $this->substituteSettings($this->documentSettings['scopes']['agenda']['documentFile']);

		$pdfView = new \OliverHader\PdfRendering\View\PdfView();
		$pdfView->setTemplatePathAndFilename($template);
		$pdfView->assign('source', $source);
		$pdfView->assign('subject', $meeting);

		$filePath = $this->providerMeetingPath($meeting) . '-agenda.pdf';
		$pdfView->save($filePath);
		return $this->resourceManager->importResource($filePath);
	}

	/**
	 * @param Meeting $meeting
	 * @return \TYPO3\Flow\Resource\Resource
	 */
	public function exportProtocol(Meeting $meeting) {
		$template = $this->substituteSettings($this->documentSettings['scopes']['protocol']['templateFile']);
		$source = $this->substituteSettings($this->documentSettings['scopes']['protocol']['documentFile']);

		$pdfView = new \OliverHader\PdfRendering\View\PdfView();
		$pdfView->setTemplatePathAndFilename($template);
		$pdfView->assign('source', $source);
		$pdfView->assign('subject', $meeting);

		$filePath = $this->providerMeetingPath($meeting) . '-protocol.pdf';
		$pdfView->save($filePath);
		return $this->resourceManager->importResource($filePath);
	}

	/**
	 * @param Meeting $meeting
	 * @return string
	 */
	protected function providerMeetingPath(Meeting $meeting) {
		return $this->providePath() . $this->persistenceManager->getIdentifierByObject($meeting);
	}

	/**
	 * @return mixed
	 */
	protected function providePath() {
		$path = rtrim($this->documentSettings['persistence']['path'], '/') . '/';
		if (!is_dir($path)) {
			mkdir($path, '0755', TRUE);
		}
		return $path;
	}

	/**
	 * @param string $value
	 * @return string
	 * @throws \TYPO3\Flow\Package\Exception\UnknownPackageException
	 */
	protected function substituteSettings($value) {
		return str_replace(
			'%PACKAGE_RESOURCES%',
			$this->packageManager->getPackage(\OpenAgenda\Application\Package::PackageName)->getResourcesPath(),
			$value
		);
	}

}