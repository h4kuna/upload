<?php

namespace h4kuna\Upload;

use Nette\Http;

interface IDriver
{

	/**
	 * Return string like absolute path or URL to file.
	 * This return string will read by fopen function.
	 * @param IStoreFile|string $relativePath
	 * @return string
	 */
	function createURI($relativePath);

	/**
	 * Check if file exists.
	 * @var IStoreFile|string $relativePath
	 * @return bool
	 */
	function isFileExists($relativePath);

	/**
	 * @param Http\FileUpload $fileUpload
	 * @param IStoreFile|string $relativePath
	 */
	function save(Http\FileUpload $fileUpload, $relativePath);

	/**
	 * Remove file from filesystem.
	 * @param IStoreFile|string $relativePath
	 * @return bool
	 */
	function remove($relativePath);

}