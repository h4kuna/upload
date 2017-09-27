<?php

namespace h4kuna\Upload\Store;

use h4kuna\Upload;

class File implements Upload\IStoreFile
{

	/** @var string */
	private $relativePath;

	/** @var string */
	private $name;

	/** @var string */
	private $contentType;

	/** @var array */
	private $extend = [];


	public function __construct($relativePath, $name, $contentType)
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
			throw new Upload\InvalidArgumentException('This name "' . $name . '" does not exists.');
		}

		return $this->extend[$name];
	}


	/**
	 * @return string
	 */
	public function getRelativePath()
	{
		return $this->relativePath;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}


	public function __toString()
	{
		return (string) $this->getRelativePath();
	}
}