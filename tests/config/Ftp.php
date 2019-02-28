<?php declare(strict_types=1);

if (class_exists('Ftp')) {
	return;
}

class Ftp extends \stdClass
{

	public function connect() { }


	public function login() { }


	public function pasv() { }


	public function fileExists($file)
	{
	}


	public function put($a, $b, $c)
	{
	}


	public function delete($file)
	{
	}


	public function mkDirRecursive($path)
	{
	}

}
