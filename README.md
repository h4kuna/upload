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
```

Inject Upload class to your class and use it.
```php
/* @var $upload h4kuna\Upload\Upload */
$upload = $container->getService('uploadExtension.upload');


/* @var $file Nette\Http\FileUpload */
$relativeDir = $upload->save($file);
// or
$relativeDir = $upload->save($file, 'subdir/by/id');

if($relativeDir !== NULL) {
	// example how to save to database
	$fileTable->insert([
		'name' => $uploadFile->getName(),
		'size' => $uploadFile->getSize(),
		'filesystem' => $relativePath,
		'type' => $uploadFile->getContentType(),
	]);
} else {
	// upload is faild
}
```

Now create absolute path for download file.
```php
/* @var $documentRoot h4kuna\Upload\DocumentRoot */
$documentRoot = $container->getService('uploadExtension.documentRoot');

// this create from relative path whose stored in database, absolute path for download file
dump($documentRoot->createAbsolutePath($fileTable->get(1)->filesystem));
```
