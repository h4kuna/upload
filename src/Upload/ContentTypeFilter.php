<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload;

use h4kuna\Upload\Exceptions\InvalidArgument;
use Nette\Http;

class ContentTypeFilter
{

	/** @var UploadControlFake */
	private static $uploadControl;

	/** @var string[] */
	private $values;


	public function __construct(...$values)
	{
		$this->values = $values;
		if ($values === []) {
			throw new InvalidArgument('Parameter $values must be non-empty.');
		}
	}


	public function isValid(Http\FileUpload $fileUpload): bool
	{
		return self::getUploadControl()->isValid($this, $fileUpload);
	}


	/**
	 * @return string[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}


	private static function getUploadControl(): UploadControlFake
	{
		if (self::$uploadControl === null) {
			self::$uploadControl = new UploadControlFake;
		}
		return self::$uploadControl;
	}

}
