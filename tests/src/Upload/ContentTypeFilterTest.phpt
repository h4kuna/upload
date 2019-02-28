<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload;

use Tester\Assert;

include __DIR__ . '/../../bootsrap.php';

Assert::exception(function () {
	new ContentTypeFilter();
}, \h4kuna\Upload\Exceptions\InvalidArgument::class);

class FileUpload extends \Nette\Http\FileUpload
{

	private $contentType;

	public function __construct($contentType)
	{
		$this->contentType = $contentType;
		parent::__construct(['name' => 'foo', 'size' => 1986, 'tmp_name' => 'bar', 'error' => UPLOAD_ERR_OK]);
	}

	public function getContentType()
	{
		return $this->contentType;
	}

}

// content type
$uploadFilter = new ContentTypeFilter('application/json', 'plain/text');
Assert::true($uploadFilter->isValid(new FileUpload('application/json')));
Assert::true($uploadFilter->isValid(new FileUpload('PLAIN/TEXT')));
Assert::false($uploadFilter->isValid(new FileUpload('foo/bar')));
