<?php

namespace h4kuna\Upload\DI;

use Nette\DI;

class UploadExtension extends DI\CompilerExtension
{

	private $defaults = [
		'ftp' => [],
		'destinations' => '',
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


	public function __construct($wwwDir = null)
	{
		$this->defaults['destinations'] = $wwwDir . DIRECTORY_SEPARATOR . 'upload';
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config + $this->defaults;;
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
				->setFactory('Ftp')
				->setAutowired(false)
				->addSetup('connect', [$options['host'], $options['port']])
				->addSetup('login', [$options['user'], $options['password']])
				->addSetup('pasv', [$options['passive']]);
			if ($options['path']) {
				$ftp->addSetup('chdir', [$options['path']]);
			}

			$config['destinations'][$name] = $builder->addDefinition($this->prefix('driver.' . $name))
				->setFactory('h4kuna\Upload\Driver\Ftp', [$options['url'], $ftp]);
		}

		// Filename
		$filename = $builder->addDefinition($this->prefix('filename'))
			->setFactory('h4kuna\Upload\Store\Filename');

		$autowired = true;
		foreach ($config['destinations'] as $info => $destination) {
			if (is_string($destination) && is_dir($destination)) {
				$definition = $builder->addDefinition($this->prefix('driver.' . $info))
					->setFactory('h4kuna\Upload\Driver\LocalFilesystem', [$destination])
					->setAutowired($autowired);
			} elseif ($destination instanceof DI\ServiceDefinition) {
				$definition = $destination->setAutowired($autowired);
			} else {
				$definition = $destination;
			}

			// Download
			$builder->addDefinition($this->prefix('download.' . $info))
				->setFactory('h4kuna\Upload\Download', [$definition, '@http.request', '@http.response'])
				->setAutowired($autowired);

			// Upload
			$builder->addDefinition($this->prefix('upload.' . $info))
				->setFactory('h4kuna\Upload\Upload', [$info, $definition, $filename])
				->setAutowired($autowired);

			$autowired = false;
		}
	}
}
