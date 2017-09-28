<?php

include __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Ftp')) {
	class Ftp extends \stdClass
	{
		public function connect() { }
		public function login() { }
		public function pasv() { }
	}
}

define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
@mkdir(TEMP_DIR, 0755, true);
@mkdir(TEMP_DIR . '/upload');
@mkdir(TEMP_DIR . '/private');

Tester\Environment::setup();
