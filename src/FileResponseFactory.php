<?php

namespace h4kuna\Upload;

use Nette\Application,
	Nette\Http;

class FileResponseFactory
{

	/** @var Http\Request */
	private $request;

	/** @var Http\Response */
	private $response;

	/** @var DocumentRoot */
	private $documentRoot;

	public function __construct(Http\Request $request, Http\Response $response, DocumentRoot $documentRoot)
	{
		$this->request = $request;
		$this->response = $response;
		$this->documentRoot = $documentRoot;
	}

	/**
	 * @param IStoreFile $file
	 * @param bool $forceDownload
	 * @return Application\Responses\FileResponse
	 */
	public function create(IStoreFile $file, $forceDownload = TRUE)
	{
		return new Application\Responses\FileResponse($this->documentRoot->createAbsolutePath($file), $file->getName(), $file->getContentType(), $forceDownload);
	}

	/**
	 * @param IStoreFile $file
	 * @param bool $forceDownload
	 * @throws FileDownloadFaildException
	 */
	public function send(IStoreFile $file, $forceDownload = TRUE)
	{
		try {
			$this->create($file, $forceDownload)->send($this->request, $this->response);
		} catch (Application\BadRequestException $e) {
			throw new FileDownloadFaildException($e->getMessage(), NULL, $e);
		}
	}

}
