<?php

namespace h4kuna\Upload;

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
}