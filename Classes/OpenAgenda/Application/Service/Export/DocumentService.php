<?php
namespace OpenAgenda\Application\Service\Export;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "OpenAgenda.Application".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use OpenAgenda\Application\Domain\Model\Meeting;

/**
 * Class DocumentService
 *
 * The service handles the creation of PDF documents.
 *
 * *Settings*
 *
 * *in Configuration/Settings.yaml or any other context specific
 * configuration file of the global TYPO3 Flow instance*
 *
 * `
 * OpenAgenda:
 *   Application:
 *     Export:
 *       Document:
 *         persistence:
 *           path: '%FLOW_PATH_DATA%/Persistent/Documents/'
 *         scopes:
 *           agenda:
 *             templateFile: '%PACKAGE_RESOURCES%/Private/Document/Agenda.html'
 *             documentFile: '%PACKAGE_RESOURCES%/Private/Document/Template.pdf'
 *           protocol:
 *             templateFile: '%PACKAGE_RESOURCES%/Private/Document/Protocol.html'
 *             documentFile: '%PACKAGE_RESOURCES%/Private/Document/Template.pdf'
 * `
 *
 * @package OpenAgenda\Application\Service\Export
 * @author Oliver Hader <oliver@typo3.org>
 */
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
	 * Exports the agenda as PDF document from a given Meeting entity.
	 * An agenda is being used prior to a meeting being executed or finished.
	 *
	 * @param Meeting $meeting The meeting entity to be used
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
	 * Exports the protocol as PDF document from a given Meeting entity.
	 * A protocol is being used once a meeting has been finished.
	 *
	 * @param Meeting $meeting The meeting entity to be used
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
	 * Provides the path to be used for PDF document persistence in the file system.
	 * The UUID of a given meeting is used a file name then.
	 *
	 * @param Meeting $meeting The meeting entity to be used
	 * @return string The path without file extension, thus no trailing ".pdf"
	 */
	protected function providerMeetingPath(Meeting $meeting) {
		return $this->providePath() . $this->persistenceManager->getIdentifierByObject($meeting);
	}

	/**
	 * Provides the base path to be used for persistence in the file system.
	 * If the path does not exist, it will automatically be created.
	 *
	 * @return string The base path
	 */
	protected function providePath() {
		$path = rtrim($this->documentSettings['persistence']['path'], '/') . '/';
		if (!is_dir($path)) {
			mkdir($path, '0755', TRUE);
		}
		return $path;
	}

	/**
	 * Substitutes path names in the available settings.
	 *
	 * @param string $value The setting value to be worked on
	 * @return string Final setting value with substituted path names
	 * @throws \TYPO3\Flow\Package\Exception\UnknownPackageException
	 * @see DocumentService::getResourcePath
	 */
	protected function substituteSettings($value) {
		return str_replace(
			'%PACKAGE_RESOURCES%',
			$this->packageManager->getPackage(\OpenAgenda\Application\Package::PackageName)->getResourcesPath(),
			$value
		);
	}

}