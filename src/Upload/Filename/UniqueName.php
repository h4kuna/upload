<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload\Filename;

use h4kuna\Upload\Upload\IFileName;
use h4kuna\Upload\Utils;
use Nette\Http;

class UniqueName implements IFileName
{

	public function createUniqueName(Http\FileUpload $fileUpload): string
	{
		$ext = Utils::extension($fileUpload);
		return sha1(microtime(true) . '.' . $fileUpload->getName()) . '.' . $ext;
	}

}
