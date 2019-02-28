<?php declare(strict_types=1);

namespace h4kuna\Upload\Upload\Filename;

use h4kuna\Upload\Upload\IFileName;
use h4kuna\Upload\Utils;
use Nette\Http;

class UniquePath implements IFileName
{

	/** @var int */
	private $length = 4;

	/** @var int */
	private $middle = 2;

	/**
	 * @param int $length
	 */
	public function setLength($length)
	{
		$this->length = $length;
	}

	/**
	 * @param int $middle
	 */
	public function setMiddle($middle)
	{
		$this->middle = $middle;
	}

	/**
	 * @param Http\FileUpload $fileUpload
	 * @return string
	 */
	public function createUniqueName(Http\FileUpload $fileUpload)
	{
		$ext = Utils::extension($fileUpload);
		$nameSplit = str_split(sha1(microtime(true) . '.' . $fileUpload->getName()), $this->length);
		return implode(DIRECTORY_SEPARATOR, array_slice($nameSplit, 0, $this->middle)) . DIRECTORY_SEPARATOR . implode(array_slice($nameSplit, $this->middle)) . '.' . $ext;
	}

}
