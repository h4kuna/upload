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
	 * @return string|NULL
	 */
	public function save(Http\FileUpload $fileUpload, $destination = '')
	{
		if (!$fileUpload->isOk()) {
			return NULL;
		}

		do {
			$relativePath = $this->createName($fileUpload, $destination);
			$pathname = $this->documentRoot->createAbsolutePath($relativePath);
		} while (is_file($pathname));

		$fileUpload->move($pathname);
		return $relativePath;
	}

	/**
	 * Prepare file name for save to file system.
	 * @param Http\FileUpload $fileUpload
	 * @param string $destination
	 * @return string
	 */
	protected function createName(Http\FileUpload $fileUpload, $destination)
	{
		$ext = pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);
		if ($destination) {
			$destination = trim($destination, '\/') . DIRECTORY_SEPARATOR;
		}
		return $destination . sha1(microtime(TRUE) . '.' . $fileUpload->getName()) . '.' . $ext;
	}

}
