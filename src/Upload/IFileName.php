<?php
/**
 * Author: Ivo Toman
 */

namespace h4kuna\Upload\Upload;

use Nette\Http;

interface IFileName
{
	/**
	 * @param Http\FileUpload $fileUpload
	 * @return string
	 */
	function createUniqueName(Http\FileUpload $fileUpload);
}