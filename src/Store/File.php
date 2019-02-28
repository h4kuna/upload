<?php declare(strict_types=1);

namespace h4kuna\Upload\Store;

use h4kuna\Upload;

class File implements Upload\IStoreFile
{

	/** @var string */
	private $relativePath;

	/** @var string */
	private $name;

	/** @var string|null */
	private $contentType;

	/** @var mixed[] */
	private $extend = [];


	public function __construct(string $relativePath, string $name, ?string $contentType)
	{
		$this->relativePath = $relativePath;
		$this->name = $name;
		$this->contentType = $contentType;
	}


	public function __set($name, $value)
	{
		return $this->extend[$name] = $value;
	}


	public function __get($name)
	{
		if (!isset($this->extend[$name])) {
			throw new Upload\Exceptions\InvalidArgument('This name "' . $name . '" does not exists.');
		}

		return $this->extend[$name];
	}


	public function getRelativePath(): string
	{
		return $this->relativePath;
	}


	public function getName(): string
	{
		return $this->name;
	}


	public function getContentType(): ?string
	{
		return $this->contentType;
	}


	public function __toString()
	{
		return $this->getRelativePath();
	}

}
