<?php declare(strict_types=1);

namespace h4kuna\Upload;

interface IStoreFile
{

	function getRelativePath(): string;

	function getName(): string;

	function getContentType(): ?string;

}
