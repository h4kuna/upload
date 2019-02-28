<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload\Filename;

use Nette\Http\FileUpload;
use Tester\Assert;

include __DIR__ . '/../../../bootsrap.php';

function createFileUpload($name = 'bar.txt')
{
	return new FileUpload([
		'name' => $name,
		'size' => rand(1, 10),
		'tmp_name' => '/tmp/foo',
		'error' => UPLOAD_ERR_OK,
	]);
}

$uniquePath = new UniquePath();
$name = $uniquePath->createUniqueName(createFileUpload());
Assert::truthy(preg_match('~^([a-z0-9]{4}/){2}[a-z0-9]{32}\.txt$~', $name));

$uniquePath->setLength(5);
$uniquePath->setMiddle(3);
$name = $uniquePath->createUniqueName(createFileUpload());
Assert::truthy(preg_match('~^([a-z0-9]{5}/){3}[a-z0-9]{25}\.txt$~', $name));
