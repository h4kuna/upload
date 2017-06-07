<?php

namespace h4kuna\Upload\DI;

use Nette\DI\CompilerExtension;

class UploadExtension extends CompilerExtension
{

	public $defaults = [
		'destinationDir' => '%wwwDir%/upload'
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		self::checkDestinationDir($config['destinationDir']);

		// DocumentRoot
		$builder->addDefinition($this->prefix('documentRoot'))
			->setClass('h4kuna\Upload\DocumentRoot', [$config['destinationDir']]);

		// FileResponseFactory
		$builder->addDefinition($this->prefix('fileResponseFactory'))
			->setClass('h4kuna\Upload\FileResponseFactory');

		$builder->addDefinition($this->prefix('upload'))
			->setClass('h4kuna\Upload\Upload');
	}

	static private function checkDestinationDir($destinationDir)
	{
		if (!is_dir($destinationDir)) {
			throw new \RuntimeException('Writeable directory not found: ' . $destinationDir);
		}

		if (!is_writable($destinationDir)) {
			throw new \RuntimeException('Set writeable permision for: ' . $destinationDir);
		}
	}

}
