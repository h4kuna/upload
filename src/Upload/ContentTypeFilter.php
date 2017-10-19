<?php

namespace h4kuna\Upload\Upload;

use h4kuna\Upload\InvalidArgumentException,
	Nette\Http;

class ContentTypeFilter
{

	/** @var array */
	private $values;

	/** @var UploadControlFake */
	private static $uploadControl;


	public function __construct(...$values)
	{
		$this->values = $values;
		if ($values === []) {
			throw new InvalidArgumentException('Parameter $values must be non-empty.');
		}
	}


	/**
	 * @param Http\FileUpload $fileUpload
	 * @return bool
	 */
	public function isValid(Http\FileUpload $fileUpload)
	{
		return self::getUploadControl()->isValid($this, $fileUpload);
	}


	/**
	 * @return array
	 */
	public function getValues()
	{
		return $this->values;
	}


	private static function getUploadControl()
	{
		if (self::$uploadControl === null) {
			self::$uploadControl = new UploadControlFake;
		}
		return self::$uploadControl;
	}

}