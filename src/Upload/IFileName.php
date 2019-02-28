<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload;

use Nette\Http;

/**
 * Author: Ivo Toman
 */
interface IFileName
{

	function createUniqueName(Http\FileUpload $fileUpload): string;

}
