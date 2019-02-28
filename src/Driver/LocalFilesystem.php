<?php declare(strict_types=1);

namespace h4kuna\Upload\Driver;

use h4kuna\Upload;
use Nette\Http;

class LocalFilesystem implements Upload\IDriver
{

	/** @var string */
	private $destinationDir;


	public function __construct(string $destinationDir)
	{
		$this->destinationDir = $destinationDir;
	}


	public function save(Http\FileUpload $fileUpload, $relativePath): void
	{
		$fileUpload->move($this->createURI($relativePath));
	}


	public function createURI($relativePath): string
	{
		return $this->destinationDir . DIRECTORY_SEPARATOR . Upload\Utils::makeRelativePath($relativePath);
	}


	public function isFileExists($relativePath): bool
	{
		return is_file($this->createURI($relativePath));
	}


	public function remove($relativePath): bool
	{
		return @unlink($this->createURI($relativePath));
	}

}
