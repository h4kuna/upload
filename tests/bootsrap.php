<?php

include __DIR__ . '/../vendor/autoload.php';

if (!class_exists('\Ftp')) {
	class Ftp extends stdClass
	{

	}
}

// 2# Create Nette Configurator
$configurator = new Nette\Configurator;

define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
@mkdir(TEMP_DIR, 0755, true);
$configurator
	->setTempDirectory(TEMP_DIR)
	->setDebugMode(true)
	->addConfig(__DIR__ . '/config/test.neon')
	->enableDebugger(TEMP_DIR . '/..');

foreach (['local', 'ftp.local'] as $neon) {
	$local = __DIR__ . '/config/test.' . $neon . '.neon';
	if (is_file($local)) {
		$configurator->addConfig($local);
	}
}

@mkdir(TEMP_DIR . '/upload');
@mkdir(TEMP_DIR . '/private');

$container = $configurator->createContainer();

Tester\Environment::setup();

return $container;
