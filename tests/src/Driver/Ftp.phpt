<?php

namespace h4kuna\Upload\Driver;

use h4kuna\Upload\Upload,
	Tester\Assert,
	Tester\Environment;

exec('composer install');
require __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/../../bootsrap.php';

if (!is_file(__DIR__ . '/../../config/test.ftp.local.neon')) {
	Environment::skip('No ftp connection.');
}

/* @var $ftp Ftp */
$ftp = $container->getService('uploadExtension.driver.test');
$upload = new Upload($ftp);

Assert::same('http://skoleni.stredobod.cz/milan/home.txt', $ftp->createURI('home.txt'));

Assert::false($ftp->isFileExists('home.txt'));

/* @var $fileUploadFactory \Salamium\Testinium\FileUploadFactory */
$fileUploadFactory = $container->getByType('Salamium\Testinium\FileUploadFactory');


$relative1 = $upload->save($fileUploadFactory->create('home.txt'));
Assert::true($ftp->isFileExists($relative1));
// die($ftp->createURI($relative1));
Assert::true($ftp->remove($relative1));


$relative2 = $upload->save($fileUploadFactory->create('home.txt'), 'sub/dir');
// die($ftp->createURI($relative2));
Assert::true($ftp->remove($relative2));



