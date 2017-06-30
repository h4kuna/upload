<?php

namespace h4kuna\Upload;

abstract class UploadException extends \Exception {}

class InvalidArgumentException extends UploadException {}

class FileUploadFaildException extends UploadException {}
class FileDownloadFaildException extends UploadException {}