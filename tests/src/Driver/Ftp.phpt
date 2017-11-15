<?php

namespace h4kuna\Upload\Driver;

use h4kuna\Upload\Upload;
use Tester\Assert;

exec('composer install');
require __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/../../bootsrap-container.php';

if (is_file(__DIR__ . '/../../config/test.ftp.local.neon')) {
	$ftpDriver = $container->getService('uploadExtension.driver.test');
} else {
	$ftp = \Mockery::mock('Ftp');
	$ftp->shouldReceive('put');
	$ftp->shouldReceive('delete');
	$ftp->shouldReceive('mkDirRecursive');
	$ftp->shouldReceive('fileExists')->andReturn(false, false, true, false);

	$ftpDriver = new Ftp('http://skoleni.stredobod.cz/milan', $ftp);
}

/* @var $ftpDriver Ftp */

$upload = new Upload($ftpDriver);

Assert::same('http://skoleni.stredobod.cz/milan/home.txt', $ftpDriver->createURI('home.txt'));

Assert::false($ftpDriver->isFileExists('home.txt'));

/* @var $fileUploadFactory \Salamium\Testinium\FileUploadFactory */
$fileUploadFactory = $container->getByType(\Salamium\Testinium\FileUploadFactory::class);

$relative1 = $upload->save($fileUploadFactory->create('home.txt'));

Assert::true($ftpDriver->isFileExists($relative1));
// die($ftpDriver->createURI($relative1));
Assert::true($ftpDriver->remove($relative1));

$relative2 = $upload->save($fileUploadFactory->create('home.txt'), 'sub/dir');
// die($ftpDriver->createURI($relative2));
Assert::true($ftpDriver->remove($relative2));



