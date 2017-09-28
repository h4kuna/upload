<?php

namespace h4kuna\Upload\Upload;

class Filter
{

	/** @var array */
	private $filter;


	public function __construct(...$values)
	{
		$this->filter = array_flip($values);
	}


	public function isAllowed($value)
	{
		if ($this->filter === []) {
			return true;
		}
		return isset($this->filter[strtolower($value)]);
	}

}