<?php

namespace h4kuna\Upload;

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
}