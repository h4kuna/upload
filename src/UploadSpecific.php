<?php

namespace h4kuna\Upload;

use Nette\Http;

class UploadSpecific
{

	/** @var IDriver */
	private $driver;

	/** @var Options */
	private $uploadOptions;


	public function __construct(IDriver $driver, Options $uploadOptions)
	{
		$this->driver = $driver;
		$this->uploadOptions = $uploadOptions;
	}


	/**
	 * @param Http\FileUpload $fileUpload
	 * @return Store\File
	 * @throws UnSupportedFileTypeException
	 * @throws FileUploadFailedException
	 */
	public function save(Http\FileUpload $fileUpload)
	{
		return Upload::saveFileUpload($fileUpload, $this->driver, $this->uploadOptions);
	}
}