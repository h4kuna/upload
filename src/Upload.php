<?php

namespace h4kuna\Upload;

use h4kuna\Upload\Store,
	Nette\Http;

class Upload
{
	/** @var IDriver */
	private $driver;

	public function __construct(IDriver $driver)
	{
		$this->driver = $driver;
	}

	/**
	 * Output object save to database.
	 * Don't forget use nette rule Form::MIME_TYPE and Form::IMAGE.
	 * $fileUpload->isOk() nette call automaticaly.
	 *
	 * @param Http\FileUpload $fileUpload
	 * @param string $path
	 * @param callable|NULL $extendStoredFile - If you need special rules then return false if is not valid.
	 * @return Store\File
	 *
	 * @throws FileUploadFailedException
	 */
	public function save(Http\FileUpload $fileUpload, $path = '', callable $extendStoredFile = null)
	{
		if ($path) {
			$path = trim($path, '\/') . DIRECTORY_SEPARATOR;
		}

		do {
			$relativePath = $path . $this->createUniqueName($fileUpload);
		} while ($this->driver->isFileExists($relativePath));

		$storeFile = new Store\File($relativePath, $fileUpload->getName(), $fileUpload->getContentType());

		if ($extendStoredFile !== null && $extendStoredFile($storeFile, $fileUpload) === false) {
			throw new FileUploadFailedException($fileUpload->getName());
		}

		$this->driver->save($fileUpload, $relativePath);

		return $storeFile;
	}

	/**
	 * @param Http\FileUpload $fileUpload
	 * @return string
	 */
	private function createUniqueName(Http\FileUpload $fileUpload)
	{
		$fileName = $this->driver->createName($fileUpload);
		if ($fileName !== null && is_string($fileName)) {
			return $fileName;
		}
		$ext = pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);

		return sha1(microtime(true) . '.' . $fileUpload->getName()) . '.' . $ext;
	}
}
