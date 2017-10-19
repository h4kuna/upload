<?php

namespace h4kuna\Upload\Upload;

use Nette\Forms,
	Nette\Http;

/**
 * @internal
 */
class UploadControlFake extends Forms\Controls\UploadControl
{

	public function __construct()
	{
		parent::__construct(null, false);
	}


	public function isValid(ContentTypeFilter $contentTypeFilter, Http\FileUpload $fileUpload)
	{
		$this->value = $fileUpload;
		return Forms\Validator::validateMimeType($this, $contentTypeFilter->getValues());
	}
}