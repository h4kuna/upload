<?php declare(strict_types=1);

include __DIR__ . '/bootsrap.php';

// 2# Create Nette Configurator
$configurator = new Nette\Configurator;


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

$container = $configurator->createContainer();

return $container;
