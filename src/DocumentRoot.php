<?php

namespace h4kuna\Upload;

class DocumentRoot
{

	/** @var string */
	private $destinationDir;

	public function __construct($destinationDir)
	{
		$this->destinationDir = $destinationDir;
	}

	/**
	 * @param string|IStoreFile $relativePath
	 * @return string
	 */
	public function createAbsolutePath($relativePath)
	{
		if($relativePath instanceof IStoreFile) {
			$relativePath = $relativePath->getRelativePath();
		}

		return $this->destinationDir . DIRECTORY_SEPARATOR . $relativePath;
	}

}
