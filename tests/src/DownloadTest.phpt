<?php declare(strict_types=1);

namespace h4kuna\Upload;

use Tester\Assert;

$container = require __DIR__ . '/../bootsrap-container.php';

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

/** @var Download $download */
$download = $container->getService('uploadExtension.download.public');

Assert::exception(function () use ($download) {
	$download->send(new TestFile());
}, \h4kuna\Upload\FileDownloadFailedException::class);

