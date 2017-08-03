<?php

namespace h4kuna\Upload;

use Nette\Http,
	Nette\Utils,
	Tester\Assert;

$container = require __DIR__ . '/../bootsrap.php';
/* @var $fileUploadFactory \Salamium\Testinium\FileUploadFactory */
$fileUploadFactory = $container->getByType('Salamium\Testinium\FileUploadFactory');

$tempDir = __DIR__ . '/../temp/upload';
Utils\FileSystem::createDir($tempDir);

// save file
$driver = new Driver\LocalFilesystem($tempDir);
$upload = new Upload($driver);
$storedFile = $upload->save($fileUploadFactory->create('čivava.txt'));

$absolutePath = $driver->createURI($storedFile);
Assert::true(is_file($absolutePath));

// save file to sub directory
$uploadFile = $fileUploadFactory->create('čivava.txt');
$storedFile = $upload->save($uploadFile, 'my/path/is/here', function (Store\File $file, Http\FileUpload $uploadFile) {
	$file->size = filesize($uploadFile->getTemporaryFile());

	Assert::exception(function () use ($file) {
		$file->name = 'foo';
	}, 'h4kuna\Upload\InvalidArgumentException');
});

Assert::exception(function () use ($storedFile) {
	$storedFile->foo;
}, 'h4kuna\Upload\InvalidArgumentException');

Assert::true($storedFile->size > 0);
$absolutePath = $driver->createURI($storedFile);
Assert::true(is_file($absolutePath));

// upload failed
Assert::exception(function () use ($upload, $fileUploadFactory) {
	$upload->save($fileUploadFactory->create('čivava.txt', UPLOAD_ERR_NO_FILE));
}, 'h4kuna\Upload\FileUploadFailedException');

// upload custom driver
/* @var $upload Upload */
$upload = $container->getService('uploadExtension.upload.noUse');
$uploadFile = $fileUploadFactory->create('čivava.txt');
$relative = $upload->save($uploadFile);
Assert::same('civava.txt', $relative->getRelativePath());

$driver = $container->getService('noUse');
Assert::true($driver->remove($relative));

