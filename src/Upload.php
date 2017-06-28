<?php

namespace h4kuna\Upload;

use Nette\Http;

class Upload
{

	/** @var DocumentRoot */
	private $documentRoot;

	public function __construct(DocumentRoot $documentRoot)
	{
		$this->documentRoot = $documentRoot;
	}

	/**
	 * Output path save to databese.
	 * @param Http\FileUpload $fileUpload
	 * @param string $destination
	 * @throws FileUploadFaildException
	 * @return string
	 */
	public function save(Http\FileUpload $fileUpload, $destination = '')
	{
		if (!$fileUpload->isOk()) {
			throw new FileUploadFaildException($fileUpload->getName(), $fileUpload->getError());
		} elseif ($destination) {
			$destination = trim($destination, '\/') . DIRECTORY_SEPARATOR;
		}

		do {
			$relativePath = $destination . $this->createName($fileUpload);
			$pathname = $this->documentRoot->createAbsolutePath($relativePath);
		} while (is_file($pathname));

		$fileUpload->move($pathname);
		return $relativePath;
	}

	/**
	 * Prepare file name for save to file system.
	 * @param Http\FileUpload $fileUpload
	 * @return string
	 */
	protected function createName(Http\FileUpload $fileUpload)
	{
		$ext = pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);
		return sha1(microtime(TRUE) . '.' . $fileUpload->getName()) . '.' . $ext;
	}

}
