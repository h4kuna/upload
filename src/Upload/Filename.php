<?php

namespace h4kuna\Upload\Upload;

use h4kuna\Upload\Utils,
	Nette\Http;

class Filename
{

	/**
	 * @param Http\FileUpload $fileUpload
	 * @return string
	 */
	public function createUniqueName(Http\FileUpload $fileUpload)
	{
		$ext = Utils::extension($fileUpload);
		return sha1(microtime(true) . '.' . $fileUpload->getName()) . '.' . $ext;
	}
}