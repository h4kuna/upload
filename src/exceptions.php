<?php

namespace h4kuna\Upload;

abstract class UploadException extends \Exception
{

}

class InvalidArgumentException extends \InvalidArgumentException
{

}

class FileUploadFailedException extends UploadException
{

}

class FileDownloadFailedException extends UploadException
{

}