<?php

namespace h4kuna\Upload;

use h4kuna\Upload\Upload\ContentTypeFilter;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form;
use Nette\Http;

class Utils
{

	/**
	 * @param string|IStoreFile $relativePath
	 * @return string
	 */
	public static function makeRelativePath($relativePath)
	{
		if ($relativePath instanceof IStoreFile) {
			return $relativePath->getRelativePath();
		}

		return $relativePath;
	}


	/**
	 * @param Http\FileUpload $fileUpload
	 * @return string|null
	 */
	public static function extension(Http\FileUpload $fileUpload)
	{
		return pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);
	}


	/**
	 * @param ContentTypeFilter $contentTypeFilter
	 * @param UploadControl $uploadControl
	 * @param string $message
	 * @return UploadControl
	 */
	public static function setMimeTypeRule(ContentTypeFilter $contentTypeFilter, UploadControl $uploadControl, $message)
	{
		$uploadControl->addRule(Form::MIME_TYPE, $message, $contentTypeFilter->getValues());
		return $uploadControl;
	}
}