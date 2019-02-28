<?php declare(strict_types=1);

namespace h4kuna\Upload;

use h4kuna\Upload\Upload\Options;
use Nette\Http;
use Nette\Forms\Controls;

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
	 * @param Controls\UploadControl $uploadControl
	 * @param string $message
	 * @return Controls\UploadControl
	 */
	public function setMimeTypeRuleForUploadControl(Controls\UploadControl $uploadControl, $message)
	{
		if ($this->uploadOptions->getContentTypeFilter() !== null) {
			Utils::setMimeTypeRule($this->uploadOptions->getContentTypeFilter(), $uploadControl, $message);
		}
		return $uploadControl;
	}

	/**
	 * @param Http\FileUpload $fileUpload
	 * @return Store\File
	 * @throws Exceptions\UnSupportedFileType
	 * @throws Exceptions\FileUploadFailed
	 */
	public function save(Http\FileUpload $fileUpload)
	{
		return Upload::saveFileUpload($fileUpload, $this->driver, $this->uploadOptions);
	}

}
