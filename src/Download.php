<?php

namespace h4kuna\Upload;

use Nette\Application;
use Nette\Http;

class Download
{

	/** @var IDriver */
	private $driver;

	/** @var Http\Request */
	private $request;

	/** @var Http\Response */
	private $response;

	public function __construct(IDriver $driver, Http\Request $request, Http\Response $response)
	{
		$this->driver = $driver;
		$this->request = $request;
		$this->response = $response;
	}

	/**
	 * @param IStoreFile $file
	 * @param bool $forceDownload
	 * @return Application\Responses\FileResponse
	 */
	public function createFileResponse(IStoreFile $file, $forceDownload = true)
	{
		return new Application\Responses\FileResponse(
			$this->driver->createURI($file),
			$file->getName(), $file->getContentType(), $forceDownload);
	}

	/**
	 * @param IStoreFile $file
	 * @param bool $forceDownload
	 * @throws FileDownloadFailedException
	 */
	public function send(IStoreFile $file, $forceDownload = true)
	{
		try {
			$this->createFileResponse($file, $forceDownload)->send($this->request, $this->response);
		} catch (Application\BadRequestException $e) {
			throw new FileDownloadFailedException($e->getMessage(), null, $e);
		}
	}
}
