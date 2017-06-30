<?php

namespace h4kuna\Upload;

$container = require __DIR__ . '/../bootsrap.php';

class DocumentRootTest extends \Tester\TestCase
{

	/** @var DocumentRoot */
	private $documentRoot;

	public function __construct(DocumentRoot $documentRoot)
	{
		$this->documentRoot = $documentRoot;
	}

	/**
	 * @throws h4kuna\Upload\InvalidArgumentException
	 */
	public function testAliasDoesNotExist()
	{
		$this->documentRoot->createAbsolutePath('/x/y/z/', 'foo');
	}

}

$documentRoot = $container->getService('uploadExtension.documentRoot');

(new DocumentRootTest($documentRoot))->run();
