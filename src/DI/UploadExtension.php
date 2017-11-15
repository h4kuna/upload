<?php

namespace h4kuna\Upload\DI;

use h4kuna\Upload;
use h4kuna\Upload\Driver;
use Nette\DI;

class UploadExtension extends DI\CompilerExtension
{

	private $defaults = [
		'ftp' => [],
		'destinations' => [],
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


	public function __construct($destinations = null)
	{
		$this->defaults['destinations'] = self::prepareDestination($destinations);
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config + $this->defaults;
		$config['destinations'] = self::prepareDestination($config['destinations']);

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
				->setFactory(Driver\Ftp::class, [$options['url'], $ftp]);
		}

		if (!$config['destinations']) {
			throw new Upload\InvalidStateException('Destinations are empty! Fill it like path where to save files.');
		}

		$autowired = true;
		foreach ($config['destinations'] as $info => $destination) {
			if (is_string($destination) && is_dir($destination)) {
				$definition = $builder->addDefinition($this->prefix('driver.' . $info))
					->setFactory(Driver\LocalFilesystem::class, [$destination])
					->setAutowired($autowired);
			} elseif ($destination instanceof DI\ServiceDefinition) {
				$definition = $destination->setAutowired($autowired);
			} else {
				$definition = $destination;
			}

			// Download
			$builder->addDefinition($this->prefix('download.' . $info))
				->setFactory(Upload\Download::class, [$definition, '@http.request', '@http.response'])
				->setAutowired($autowired);

			// Upload
			$builder->addDefinition($this->prefix('upload.' . $info))
				->setFactory(Upload\Upload::class, [$definition])
				->setAutowired($autowired);

			$autowired = false;
		}
	}


	private static function prepareDestination($destination)
	{
		if (!is_array($destination)) {
			if ($destination) {
				return ['default' => $destination];
			}
			return [];
		}
		return $destination;
	}
}
