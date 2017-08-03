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
	 * Output path save to database.
	 * @param Http\FileUpload $fileUpload
	 * @param string $path
	 * @param callable|NULL $extendStoredFile
	 * @return Store\File
	 *
	 * @throws FileUploadFailedException
	 */
	public function save(Http\FileUpload $fileUpload, $path = '', callable $extendStoredFile = NULL)
	{
		if (!$fileUpload->isOk()) {
			throw new FileUploadFailedException($fileUpload->getName(), $fileUpload->getError());
		} elseif ($path) {
			$path = trim($path, '\/') . DIRECTORY_SEPARATOR;
		}

		do {
			$relativePath = $path . $this->createUniqueName($fileUpload);
		} while ($this->driver->isFileExists($relativePath));

		$storeFile = new Store\File($relativePath, $fileUpload->getName(), $fileUpload->getContentType());

		if($extendStoredFile !== NULL) {
			$extendStoredFile($storeFile, $fileUpload);
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
