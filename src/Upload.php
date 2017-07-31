<?php

namespace h4kuna\Upload;

use Nette\Http;

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
	 * @return string
	 *
	 * @throws FileUploadFailedException
	 */
	public function save(Http\FileUpload $fileUpload, $path = '')
	{
		if (!$fileUpload->isOk()) {
			throw new FileUploadFailedException($fileUpload->getName(), $fileUpload->getError());
		} elseif ($path) {
			$path = trim($path, '\/') . DIRECTORY_SEPARATOR;
		}

		do {
			$relativePath = $path . $this->createUniqueName($fileUpload);
		} while ($this->driver->isFileExists($relativePath));

		$this->driver->save($fileUpload, $relativePath);

		return $relativePath;
	}

	/**
	 * @param Http\FileUpload $fileUpload
	 * @return string
	 */
	private function createUniqueName(Http\FileUpload $fileUpload)
	{
		$fileName = $this->driver->createName($fileUpload);
		if ($fileName !== NULL && is_string($fileName)) {
			return $fileName;
		}
		$ext = pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);
		return sha1(microtime(TRUE) . '.' . $fileUpload->getName()) . '.' . $ext;
	}
}
