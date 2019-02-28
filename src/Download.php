<?php declare(strict_types=1);

namespace h4kuna\Upload;

use h4kuna\Upload\Exceptions\FileDownloadFailed;
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


	public function createFileResponse(IStoreFile $file, bool $forceDownload = true): Application\Responses\FileResponse
	{
		return new Application\Responses\FileResponse(
			$this->driver->createURI($file),
			$file->getName(), $file->getContentType(), $forceDownload);
	}

	/**
	 * @throws FileDownloadFailed
	 */
	public function send(IStoreFile $file, bool $forceDownload = true): void
	{
		try {
			$this->createFileResponse($file, $forceDownload)->send($this->request, $this->response);
		} catch (Application\BadRequestException $e) {
			throw new FileDownloadFailed($e->getMessage(), 0, $e);
		}
	}

}
