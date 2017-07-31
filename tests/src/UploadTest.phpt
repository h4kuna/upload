<?php

namespace h4kuna\Upload;


use Nette\Utils,
	Tester\Assert;

$container = require __DIR__ . '/../bootsrap.php';
/* @var $fileUploadFactory \Salamium\Testinium\FileUploadFactory */
$fileUploadFactory = $container->getByType('Salamium\Testinium\FileUploadFactory');

$tempDir = __DIR__ . '/../temp/upload';
Utils\FileSystem::createDir($tempDir);


// save file
$driver = new Driver\LocalFilesystem($tempDir);
$upload = new Upload($driver);
$relativePath = $upload->save($fileUploadFactory->create('čivava.txt'));

$absolutePath = $driver->createURI($relativePath);
Assert::true(is_file($absolutePath));


// save file to sub directory
$uploadFile = $fileUploadFactory->create('čivava.txt');
$relativePath = $upload->save($uploadFile, 'my/path/is/here');

$absolutePath = $driver->createURI($relativePath);
Assert::true(is_file($absolutePath));


// upload failed
Assert::exception(function() use ($upload, $fileUploadFactory) {
	$upload->save($fileUploadFactory->create('čivava.txt', UPLOAD_ERR_NO_FILE));
}, 'h4kuna\Upload\FileUploadFailedException');

// upload custom driver
/* @var $upload Upload */
$upload = $container->getService('uploadExtension.upload.noUse');
$uploadFile = $fileUploadFactory->create('čivava.txt');
$relative = $upload->save($uploadFile);
Assert::same('civava.txt', $relative);

$driver = $container->getService('noUse');
Assert::true($driver->remove($relative));

