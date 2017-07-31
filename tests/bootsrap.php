<?php

include __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/config/MyFileLocale.php';

Tester\Environment::setup();

if (!class_exists('\Ftp')) {
	class Ftp extends stdClass
	{
	}
}

// 2# Create Nette Configurator
$configurator = new Nette\Configurator;

$tmp = __DIR__ . '/temp';
@mkdir($tmp);
$configurator->enableDebugger($tmp);
$configurator->setTempDirectory($tmp);
$configurator->setDebugMode(false);
$configurator->addConfig(__DIR__ . '/config/test.neon');

foreach (['local', 'ftp.local'] as $neon) {
	$local = __DIR__ . '/config/test.' . $neon . '.neon';
	if (is_file($local)) {
		$configurator->addConfig($local);
	}
}

Tracy\Debugger::enable(false);

@mkdir($tmp . '/upload');
@mkdir($tmp . '/private');

$container = $configurator->createContainer();

return $container;
