<?php

use h4kuna\Upload;
use Nette\DI;
use Tester\Assert;

$container = require __DIR__ . '/../../bootsrap.php';

$compiler = new DI\Compiler();
$http = new \Nette\Bridges\HttpDI\HttpExtension();
$compiler->addExtension('http', $http);

$extension = new Upload\DI\UploadExtension(TEMP_DIR);
$extension->setConfig([
	'ftp' => [
		'dummy' => [
		],
	]
]);

$compiler->addExtension('h4kuna.upload', $extension);

//file_put_contents(__DIR__ . '/container.php', "<?php\n" . $compiler->compile());
eval($compiler->compile());

$container = new \Container();

Assert::type('Ftp', $container->getService('h4kuna.upload.ftp.dummy'));
Assert::type('h4kuna\Upload\Driver\Ftp', $container->getService('h4kuna.upload.driver.dummy'));
Assert::type('h4kuna\Upload\Download', $container->getService('h4kuna.upload.download.dummy'));
Assert::type('h4kuna\Upload\Upload', $container->getService('h4kuna.upload.upload.dummy'));
