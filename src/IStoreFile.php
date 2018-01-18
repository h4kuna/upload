<?php

namespace h4kuna\Upload;

interface IStoreFile
{

	/** @return string */
	function getRelativePath();

	/** @return string|NULL */
	function getName();

	/** @return string|NULL */
	function getContentType();

}
