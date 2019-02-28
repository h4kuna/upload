<?php declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

define('TEMP_DIR', __DIR__ . '/temp/' . getmypid());
@mkdir(TEMP_DIR, 0755, true);
@mkdir(TEMP_DIR . '/upload');
@mkdir(TEMP_DIR . '/private');

Tester\Environment::setup();
