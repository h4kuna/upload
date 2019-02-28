<?php declare(strict_types=1);

namespace h4kuna\Upload;

use h4kuna\Upload\Upload\ContentTypeFilter;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form;
use Nette\Http;

class Utils
{

	/**
	 * @param IStoreFile|string $relativePath
	 */
	public static function makeRelativePath($relativePath): string
	{
		if ($relativePath instanceof IStoreFile) {
			return $relativePath->getRelativePath();
		}

		return $relativePath;
	}


	public static function extension(Http\FileUpload $fileUpload): ?string
	{
		return pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);
	}


	public static function setMimeTypeRule(ContentTypeFilter $contentTypeFilter, UploadControl $uploadControl, string $message): UploadControl
	{
		$uploadControl->addRule(Form::MIME_TYPE, $message, $contentTypeFilter->getValues());
		return $uploadControl;
	}

}