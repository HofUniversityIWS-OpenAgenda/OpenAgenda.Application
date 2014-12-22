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
	 * @param Meeting $meeting
	 */
	public function exportAgenda(Meeting $meeting) {
		$template = $this->substituteSettings($this->documentSettings['agenda']['templateFile']);
		$source = $this->substituteSettings($this->documentSettings['agenda']['documentFile']);

		$pdfView = new \OliverHader\PdfRendering\View\PdfView();
		$pdfView->setTemplatePathAndFilename($template);
		$pdfView->assign('source', $source);
		$pdfView->assign('subject', $meeting);
		$pdfView->save(FLOW_PATH_DATA . 'Agenda.pdf');
	}

	/**
	 * @param Meeting $meeting
	 */
	public function exportProtocol(Meeting $meeting) {
		$template = $this->substituteSettings($this->documentSettings['protocol']['templateFile']);
		$source = $this->substituteSettings($this->documentSettings['protocol']['documentFile']);

		$pdfView = new \OliverHader\PdfRendering\View\PdfView();
		$pdfView->setTemplatePathAndFilename($template);
		$pdfView->assign('source', $source);
		$pdfView->assign('subject', $meeting);
		$pdfView->save(FLOW_PATH_DATA . 'Protocol.pdf');
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