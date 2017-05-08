<?php

namespace h4kuna\Upload;

interface IStoreFile
{
	/**
	 * Use this interface for entity and return string from Upload::save
	 * @return string
	 */
	function getRelativePath();
}
