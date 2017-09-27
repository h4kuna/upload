<?php

use h4kuna\Upload,
	Nette\DI,
	Tester\Assert;

if (!class_exists('Ftp')) {
	class Ftp extends \stdClass
	{
		public function connect() {}

		public function login() {}

		public function pasv() {}
	}
}

$container = require __DIR__ . '/../../../vendor/autoload.php';

$compiler = new DI\Compiler();
$compiler->addConfig([
	'parameters' => [
		'tempDir' => TEMP_DIR
	],
	'services' => [
		'http.requestFactory' => [
			'factory' => 'Salamium\Testinium\HttpRequestFactory'
		],
		'http.request' => [
			'factory' => '@http.requestFactory::create',
			'arguments' => ['http://example.com/']
		],
		'http.response' => [
			'factory' => 'Nette\Http\Response'
		],
	]
]);
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
