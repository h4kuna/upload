<?php declare(strict_types=1);

namespace h4kuna\Upload;

use Nette\Http;

interface IDriver
{

	/**
	 * Return string like absolute path or URL to file.
	 * This return string will read by fopen function.
	 * @param IStoreFile|string $relativePath
	 */
	function createURI($relativePath): string;

	/**
	 * Check if file exists.
	 * @var IStoreFile|string $relativePath
	 */
	function isFileExists($relativePath): bool;

	/**
	 * @param Http\FileUpload $fileUpload
	 * @param IStoreFile|string $relativePath
	 */
	function save(Http\FileUpload $fileUpload, $relativePath): void;

	/**
	 * Remove file from filesystem.
	 * @param IStoreFile|string $relativePath
	 */
	function remove($relativePath): bool;

}
