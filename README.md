Upload
==========
[![Build Status](https://travis-ci.org/h4kuna/upload.svg?branch=master)](https://travis-ci.org/h4kuna/upload)
[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/upload.svg)](https://packagist.org/packages/h4kuna/upload)
[![Latest stable](https://img.shields.io/packagist/v/h4kuna/upload.svg)](https://packagist.org/packages/h4kuna/upload)

This extension help you save uploded files to filesystem and save to database.

Require PHP 5.4+.

This extension is for php [Nette framework](//github.com/nette/nette).

Installation to project
-----------------------
The best way to install h4kuna/upload is using composer:
```sh
$ composer require h4kuna/upload
```

How to use
-----------
Register extension for Nette in neon config file.
```sh
extensions:
    uploadExtension: h4kuna\Upload\DI\UploadExtension

# optional
uploadExtension:
	destinationDir: %wwwDir%/upload # this is default, you must create like writeable

	# or destinationDir can by array
	destinationDir:
		pulic: %wwwDir%/upload # first is default
		private: %appDir%/private
```

Inject Upload class to your class and use it.
```php
/* @var $upload h4kuna\Upload\Upload */
$upload = $container->getService('uploadExtension.upload');


try {
	/* @var $file Nette\Http\FileUpload */
	$relativeDir = $upload->save($file);
	// or
	$relativeDir = $upload->save($file, 'subdir/by/id');

	// example how to save to database data for IStoreFile
	$fileTable->insert([
		'name' => $uploadFile->getName(),
		'filesystem' => $relativePath,
		'type' => $uploadFile->getContentType(),
		// optional
		'size' => $uploadFile->getSize(),
	]);
} catch (\h4kuna\Upload\FileUploadFaildException $e) {
	// upload is faild
}
```

If you want save to other destination file what is defined above.

```php
try {
	/* @var $file Nette\Http\FileUpload */
	$relativeDir = $upload->save($file, 'subdir/by/id', 'private');

	// ...
} catch (\h4kuna\Upload\FileUploadFaildException $e) {
	// upload is faild
}
```

Now create FileResponse for download file.
```php
/* @var $fileResponseFactory h4kuna\Upload\FileResponseFactory */
$fileResponseFactory = $container->getService('uploadExtension.fileResponseFactory');
```

If you use in presenter
```php
// this create from data whose stored in database
$file = new File(...); // instance of IStoreFile
$presenter->sendResponse($fileResponseFactory->create($file));
```

Change destination:
```php
// second parameter is force download
$presenter->sendResponse($fileResponseFactory->create($file, FALSE, 'private'));
```

Or if you use own script
```php
$file = new File(...); // instance of IStoreFile

try {
	$fileResponseFactory->send($file);
} catch (\h4kuna\Upload\FileDownloadFaildException $e) {
	$response->setCode(Nette\Http\IResponse::S404_NOT_FOUND);
}
exit;
```
