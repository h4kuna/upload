<?php

namespace h4kuna\Upload\Store;

use Nette\Http;

class Filename
{

	/**
	 * @param Http\FileUpload $fileUpload
	 * @param string $name - name of Upload service
	 * @return string
	 */
	public function createUniqueName(Http\FileUpload $fileUpload, $name)
	{
		$ext = pathinfo($fileUpload->getName(), PATHINFO_EXTENSION);
		return sha1(microtime(true) . '.' . $fileUpload->getName()) . '.' . $ext;
	}
}