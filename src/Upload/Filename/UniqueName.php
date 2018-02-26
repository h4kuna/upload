<?php

namespace h4kuna\Upload\Upload\Filename;

use h4kuna\Upload\Upload\IFileName;
use h4kuna\Upload\Utils;
use Nette\Http;

class UniqueName implements IFileName
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