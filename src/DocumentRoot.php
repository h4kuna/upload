<?php

namespace h4kuna\Upload;

class DocumentRoot
{

	/** @var array */
	private $destinationDirs;

	/** @var string */
	private $default;

	public function __construct(array $destinationDirs)
	{
		$this->destinationDirs = $destinationDirs;
		reset($destinationDirs);
		$this->default = key($destinationDirs);
	}

	/**
	 * @param string|IStoreFile $relativePath
	 * @param string|NULL $destinationAlias
	 * @return string
	 */
	public function createAbsolutePath($relativePath, $destinationAlias = NULL)
	{
		if ($relativePath instanceof IStoreFile) {
			$relativePath = $relativePath->getRelativePath();
		}

		if ($destinationAlias === NULL) {
			$path = $this->destinationDirs[$this->default];
		} elseif (isset($this->destinationDirs[$destinationAlias])) {
			$path = $this->destinationDirs[$destinationAlias];
		} else {
			throw new InvalidArgumentException('Destination alias does not exists "' . $destinationAlias . '".');
		}

		return $path . DIRECTORY_SEPARATOR . $relativePath;
	}

}
