<?php

namespace h4kuna\Upload\Upload;

use h4kuna\Upload\Store;
use Nette\Http;

final class Options
{

	/** @var string */
	private $path = '';

	/** @var callback */
	private $extendStoredFile;

	/** @var Filename */
	private $filename;

	/** @var ContentTypeFilter */
	private $contentTypeFilter;

	public function __construct($path = '', callable $extendStoredFile = null, Filename $filename = null, ContentTypeFilter $contentTypeFilter = null)
	{
		if ($path !== '') {
			$path = trim($path, '\/') . DIRECTORY_SEPARATOR;
		}

		$this->path = $path;
		$this->filename = $filename ?: new Filename();
		$this->contentTypeFilter = $contentTypeFilter;
		$this->extendStoredFile = $extendStoredFile;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @param Store\File $storeFile
	 * @param Http\FileUpload $fileUpload
	 */
	public function runExtendStoredFile(Store\File $storeFile, Http\FileUpload $fileUpload)
	{
		$this->extendStoredFile !== null && call_user_func($this->extendStoredFile, $storeFile, $fileUpload);
	}

	/**
	 * @return Filename
	 */
	public function getFilename()
	{
		return $this->filename;
	}

	/**
	 * @return ContentTypeFilter|null
	 */
	public function getContentTypeFilter()
	{
		return $this->contentTypeFilter;
	}

}