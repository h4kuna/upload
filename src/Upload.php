<?php

namespace h4kuna\Upload;

use h4kuna\Upload\Store,
	h4kuna\Upload\Upload\Options,
	Nette\Http;

class Upload
{

	/** @var IDriver */
	private $driver;

	/** @var Options[] */
	private $uploadOptions = [];


	public function __construct(IDriver $driver)
	{
		$this->driver = $driver;
	}


	/**
	 * @param Http\FileUpload $fileUpload
	 * @param Options|string|null $uploadOptions - string is path
	 * @return Store\File
	 * @throws FileUploadFailedException
	 */
	public function save(Http\FileUpload $fileUpload, $uploadOptions = null)
	{
		if ($uploadOptions === null || is_scalar($uploadOptions)) {
			$uploadOptions = $this->getUploadOptions((string) $uploadOptions);
		} elseif (!$uploadOptions instanceof Options) {
			throw new InvalidArgumentException('Second parameter must be instance of UploadOptions or null or string.');
		}

		return self::saveFileUpload($fileUpload, $this->driver, $uploadOptions);
	}


	/**
	 * @param string $key
	 * @return Options
	 */
	private function getUploadOptions($key)
	{
		if (!isset($this->uploadOptions[$key])) {
			$this->uploadOptions[$key] = new Options($key);
		}
		return $this->uploadOptions[$key];
	}


	/**
	 * Output object save to database.
	 * Don't forget use nette rule Form::MIME_TYPE and Form::IMAGE.
	 * $fileUpload->isOk() nette call automatically.
	 * @param Http\FileUpload $fileUpload
	 * @param IDriver $driver
	 * @param Options $uploadOptions
	 * @return Store\File
	 * @throws FileUploadFailedException
	 * @throws UnSupportedFileTypeException
	 */
	public static function saveFileUpload(Http\FileUpload $fileUpload, IDriver $driver, Options $uploadOptions)
	{
		if ($uploadOptions->getContentTypeFilter() && !$uploadOptions->getContentTypeFilter()->isValid($fileUpload)) { // if forgot use Utils::setMimeTypeRule();
			throw new UnSupportedFileTypeException('name: ' . $fileUpload->getName() . ', type: ' . $fileUpload->getContentType());
		}

		do {
			$relativePath = $uploadOptions->getPath() . $uploadOptions->getFilename()->createUniqueName($fileUpload);
		} while ($driver->isFileExists($relativePath));

		$storeFile = new Store\File($relativePath, $fileUpload->getName(), $fileUpload->getContentType());

		$uploadOptions->runExtendStoredFile($storeFile, $fileUpload);

		try {
			$driver->save($fileUpload, $relativePath);
		} catch (\Exception $e) {
			throw new FileUploadFailedException('Driver "' . get_class($driver) . '" failed.', null, $e);
		}

		return $storeFile;
	}

}
