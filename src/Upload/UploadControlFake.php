<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload;

use Nette\Forms;
use Nette\Http;

/**
 * @internal
 */
class UploadControlFake extends Forms\Controls\UploadControl
{

	public function __construct()
	{
		parent::__construct(null, false);
	}


	public function isValid(ContentTypeFilter $contentTypeFilter, Http\FileUpload $fileUpload): bool
	{
		$this->value = $fileUpload;
		return Forms\Validator::validateMimeType($this, $contentTypeFilter->getValues());
	}

}
