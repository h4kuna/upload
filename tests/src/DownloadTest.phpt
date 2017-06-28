<?php

namespace h4kuna\Upload;

$container = require __DIR__ . '/../bootsrap.php';

class DownloadTest extends \Tester\TestCase
{

	/** @var FileResponseFactory */
	private $fileResponseFactory;

	public function __construct(FileResponseFactory $fileResponseFactory)
	{
		$this->fileResponseFactory = $fileResponseFactory;
	}

	/**
	 * @throws h4kuna\Upload\FileDownloadFaildException
	 */
	public function testUploadFaild()
	{
		$this->fileResponseFactory->send(new TestFile());
	}

}

class TestFile implements IStoreFile
{

	public function getContentType()
	{
		return 'unknown';
	}

	public function getName()
	{
		return 'unknown';
	}

	public function getRelativePath()
	{
		return '/no/file/exists';
	}

}

$fileResponseFactory = $container->getService('uploadExtension.fileResponseFactory');

(new DownloadTest($fileResponseFactory))->run();
