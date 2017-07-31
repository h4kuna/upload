<?php

namespace h4kuna\Upload;

use h4kuna\Upload\Driver,
	Nette\Http;

class MyFileLocale extends Driver\LocalFilesystem
{
	public function createName(Http\FileUpload $fileUpload)
	{
		return $fileUpload->getSanitizedName();
	}
}