<?php declare(strict_types=1);

namespace h4kuna\Upload\Driver;

use h4kuna\Upload;
use Nette\Http;

class Ftp implements Upload\IDriver
{

	/** @var string */
	private $hostUrl;

	/** @var \Ftp */
	private $ftp;


	public function __construct(string $hostUrl, \Ftp $ftp)
	{
		$this->hostUrl = $hostUrl;
		$this->ftp = $ftp;
	}


	public function createURI($relativePath): string
	{
		return $this->hostUrl . '/' . Upload\Utils::makeRelativePath($relativePath);
	}


	public function isFileExists($relativePath): bool
	{
		return $this->ftp->fileExists(Upload\Utils::makeRelativePath($relativePath));
	}


	public function save(Http\FileUpload $fileUpload, $relativePath): void
	{
		$path = Upload\Utils::makeRelativePath($relativePath);
		$dir = dirname($relativePath);
		if ($dir !== '.') {
			$this->ftp->mkDirRecursive($dir);
		}
		$this->ftp->put($path, $fileUpload->getTemporaryFile(), FTP_BINARY);
		@unlink($fileUpload->getTemporaryFile());
	}


	public function remove($relativePath): bool
	{
		$path = Upload\Utils::makeRelativePath($relativePath);
		$this->ftp->delete($path);

		return !$this->isFileExists($path);
	}

}
