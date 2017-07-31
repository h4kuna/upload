<?php

namespace h4kuna\Upload\DI;

use Nette\DI;

class UploadExtension extends DI\CompilerExtension
{
	private $defaults = [
		'destinations' => '%wwwDir%/upload',
	];

	public function loadConfiguration()
	{
		$this->config += $this->defaults;
		$builder = $this->getContainerBuilder();
		$config = DI\Helpers::expand($this->config, $builder->parameters);
		if (!is_array($config['destinations'])) {
			$config['destinations'] = ['default' => $config['destinations']];
		}

		$autowired = true;
		foreach ($config['destinations'] as $i => $destination) {
			if (is_dir($destination)) {
				$definition = $builder->addDefinition($this->prefix('driver.' . $i))
					->setClass('h4kuna\Upload\Driver\LocalFilesystem', [$destination])
					->setAutowired($autowired);
			} else {
				$definition = $destination;
			}

			// Download
			$builder->addDefinition($this->prefix('download.' . $i))
				->setClass('h4kuna\Upload\Download', [$definition, '@http.request', '@http.response'])
				->setAutowired($autowired);

			// Upload
			$builder->addDefinition($this->prefix('upload.' . $i))
				->setClass('h4kuna\Upload\Upload', [$definition])
				->setAutowired($autowired);

			$autowired = false;
		}
	}
}
