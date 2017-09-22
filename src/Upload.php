<?php

namespace h4kuna\Upload;

use h4kuna\Upload\Store,
	Nette\Http;

class Upload
{
	/** @var string */
	private $name;

	/** @var IDriver */
	private $driver;

	/** @var Store\Filename */
	private $filename;

	public function __construct($name, IDriver $driver, Store\Filename $filename)
	{
		$this->name = $name;
		$this->driver = $driver;
		$this->filename = $filename;
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
			$relativePath = $path . $this->filename->createUniqueName($fileUpload, $this->name);
		} while ($this->driver->isFileExists($relativePath));

		$storeFile = new Store\File($relativePath, $fileUpload->getName(), $fileUpload->getContentType());

		if ($extendStoredFile !== null && $extendStoredFile($storeFile, $fileUpload) === false) {
			throw new FileUploadFailedException($fileUpload->getName());
		}

		try {
			$this->driver->save($fileUpload, $relativePath);
		} catch (\Exception $e) {
			throw new FileUploadFailedException('Driver "' . get_class($this->driver) . '" failed.', null, $e);
		}

		return $storeFile;
	}
}
