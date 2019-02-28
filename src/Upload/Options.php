<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload;

use h4kuna\Upload\Store;
use h4kuna\Upload\Upload\Filename\UniqueName;
use Nette\Http;

final class Options
{

	/** @var string */
	private $path = '';

	/** @var callable|null */
	private $extendStoredFile;

	/** @var IFileName */
	private $filename;

	/** @var ContentTypeFilter|null */
	private $contentTypeFilter;


	public function __construct(string $path = '', ?callable $extendStoredFile = null, ?IFileName $filename = null, ?ContentTypeFilter $contentTypeFilter = null)
	{
		if ($path !== '') {
			$path = trim($path, '\/') . DIRECTORY_SEPARATOR;
		}

		$this->path = $path;
		$this->filename = $filename ?: new UniqueName();
		$this->contentTypeFilter = $contentTypeFilter;
		$this->extendStoredFile = $extendStoredFile;
	}


	public function getPath(): string
	{
		return $this->path;
	}


	public function runExtendStoredFile(Store\File $storeFile, Http\FileUpload $fileUpload): void
	{
		$this->extendStoredFile !== null && call_user_func($this->extendStoredFile, $storeFile, $fileUpload);
	}


	public function getFilename(): IFileName
	{
		return $this->filename;
	}


	public function getContentTypeFilter(): ?ContentTypeFilter
	{
		return $this->contentTypeFilter;
	}

}
