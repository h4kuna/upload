<?php

namespace h4kuna\Upload\DI;

use Nette\DI;

class UploadExtension extends DI\CompilerExtension
{
	private $defaults = [
		'ftp' => [],
		'destinations' => '%wwwDir%/upload',
	];

	private $ftp = [
		'host' => '',
		'user' => '',
		'password' => '',
		'url' => '',
		'path' => '',
		'port' => 21,
		'passive' => true,
	];

	public function loadConfiguration()
	{
		$this->config += $this->defaults;
		$builder = $this->getContainerBuilder();
		$config = DI\Helpers::expand($this->config, $builder->parameters);

		if (!is_array($config['destinations'])) {
			if ($config['destinations']) {
				$config['destinations'] = ['default' => $config['destinations']];
			} else {
				$config['destinations'] = [];
			}
		}

		foreach ($config['ftp'] as $name => $options) {
			$options += $this->ftp;
			$ftp = $builder->addDefinition($this->prefix('ftp.' . $name))
				->setClass('Ftp')
				->setAutowired(false)
				->addSetup('connect', [$options['host'], $options['port']])
				->addSetup('login', [$options['user'], $options['password']])
				->addSetup('pasv', [$options['passive']]);
			if($options['path']) {
				$ftp->addSetup('chdir', [$options['path']]);
			}

			$config['destinations'][$name] = $builder->addDefinition($this->prefix('driver.' . $name))
				->setClass('h4kuna\Upload\Driver\Ftp', [$options['url'], $ftp]);
		}

		$autowired = true;
		foreach ($config['destinations'] as $i => $destination) {
			if (is_string($destination) && is_dir($destination)) {
				$definition = $builder->addDefinition($this->prefix('driver.' . $i))
					->setClass('h4kuna\Upload\Driver\LocalFilesystem', [$destination])
					->setAutowired($autowired);
			} elseif ($destination instanceof DI\ServiceDefinition) {
				$definition = $destination->setAutowired($autowired);
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
