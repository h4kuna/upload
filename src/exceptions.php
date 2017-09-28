<?php

namespace h4kuna\Upload;

class InvalidArgumentException extends \InvalidArgumentException {}

// download
abstract class DownloadException extends \Exception {}

class FileDownloadFailedException extends DownloadException {}

// upload
abstract class UploadException extends \Exception {}

class FileUploadFailedException extends UploadException {}

class UnSupportedFileTypeException extends UploadException {}