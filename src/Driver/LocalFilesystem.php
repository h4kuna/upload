<?php

namespace h4kuna\Upload\Driver;

use h4kuna\Upload,
	Nette\Http;

class LocalFilesystem implements Upload\IDriver
{
	/** @var string */
	private $destinationDir;

	public function __construct($destinationDir)
	{
		$this->destinationDir = $destinationDir;
	}

	public function save(Http\FileUpload $fileUpload, $relativePath)
	{
		$fileUpload->move($this->createURI($relativePath));
	}

	public function createURI($relativePath)
	{
		if ($relativePath instanceof Upload\IStoreFile) {
			$relativePath = $relativePath->getRelativePath();
		}
		return $this->destinationDir . DIRECTORY_SEPARATOR . $relativePath;
	}

	public function isFileExists($relativePath)
	{
		return is_file($this->createURI($relativePath));
	}

	public function createName(Http\FileUpload $fileUpload)
	{
		return NULL;
	}

	public function remove($relativePath)
	{
		return @unlink($this->createURI($relativePath));
	}
}