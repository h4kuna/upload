Upload
==========
[![Build Status](https://travis-ci.org/h4kuna/upload.svg?branch=master)](https://travis-ci.org/h4kuna/upload)
[![Downloads this Month](https://img.shields.io/packagist/dm/h4kuna/upload.svg)](https://packagist.org/packages/h4kuna/upload)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/h4kuna/upload/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/h4kuna/upload/?branch=master)
[![Latest stable](https://img.shields.io/packagist/v/h4kuna/upload.svg)](https://packagist.org/packages/h4kuna/upload)
[![Coverage Status](https://coveralls.io/repos/github/h4kuna/upload/badge.svg?branch=master)](https://coveralls.io/github/h4kuna/upload?branch=master)

This extension help you save uploded files to filesystem and prepare for database.

Require PHP 5.4+.

This extension is for php [Nette framework](//github.com/nette/nette).

Installation to project
-----------------------
The best way to install h4kuna/upload is using composer:
```sh
$ composer require h4kuna/upload
```

## Support
v 0.1.0 - 0.2.2 support php <= 5.5

For begin
-----------
Simple registration like extension
```neon
extensions:
    uploadExtension: h4kuna\Upload\DI\UploadExtension
```
You need create writeable dir **%wwwDir%/upload** and use new services.
- **uploadExtension.driver.default** object LocalFilesystem
- **uploadExtension.upload.default** object Upload with this driver
- **uploadExtension.download.default** object Download with this driver

More options
-----------
Register extension for Nette in neon config file.
```neon
# optional
uploadExtension:
	destinations: %wwwDir%/upload # this is default, you must create like writeable

	# or destinations can by array
	destinations:
		public: %wwwDir%/upload # first is default
		private: %appDir%/private # string or service h4kuna\Upload\IDriver
```

First destination (in example **public**) is autowired. For every destination is created service **upload** and **download**. 

Manually add dependency:
```neon
services:
    - UploadControl(@uploadExtension.upload.private)
    - DownloadControl(@uploadExtension.download.private)
``` 

 

Inject Upload service to your class and use it. Return an object **h4kuna\Upload\Store\File** let's save all property for later create **IStoreFile**.

Simple usage for upload:
```php
/* @var $upload h4kuna\Upload\Upload */


try {
    /* @var $file Nette\Http\FileUpload */
    
    // second parameter is optional
    $storedFile = $upload->save($file, 'path/by/id');
	

    /* @var $storedFile h4kuna\Upload\Store\File */
    $fileTable->insert([
        // required parameters for response (Download) for create IStoreFile
        'name' => $storedFile->getName(),
        'filesystem' => $storedFile->getRelativePath(),
        'type' => $storedFile->getContentType(),
        
        // optional
        'size' => $file->getSize(),
        'is_image' => $storedFile->isImage
    ]);
} catch (\h4kuna\Upload\FileUploadFailedException $e) {
	// upload is failed
}
```

For more upload options create object [h4kuna\Upload\Upload\Options](src/Upload/Options.php).

- you can define where to save
- extend IStoreFile that is returned by Upload::save()
- define behavior for save on filesystem by [h4kuna\Upload\Upload\Filename](src/Upload/Filename.php)
- create content type filter by [h4kuna\Upload\Upload\Filter](src/Upload/Filter.php)

If filter is defined than you can expect new exception **UnSupportedFileTypeException**. Both exception in example has same parent **UploadException**.

```php
$filter = h4kuna\Upload\Upload\FilterFactory::createImage();
$name = new h4kuna\Upload\Upload\Filename(); // default
try {
    $storedFile = $upload->save($file, new h4kuna\Upload\Upload\Options('subdir/by/id', function(h4kuna\Upload\Store\File $storedFile, Nette\Http\FileUpload $uploadFile) {
        // if you need add optional property like size, etc. 
        $storedFile->isImage = $uploadFile->isImage();
        
        // Custom rule: allow image bigger then 500x500px
        if ($storedFile->isImage) {
            $image = $uploadFile->getImage();
            if($image->getHeight() < 500 || $image->getWidth() < 500) {
                return false; // this return throw exception
            }
        }
    
    }, $name, $filter));
} catch (FileUploadFailedException $e) {
    // driver is failed
} catch (UnSupportedFileTypeException $e) {
    // unsupported file, filter refused
}
```

Here is prepared object [h4kuna\Upload\UploadSpecific](src/UploadSpecific.php).

Now create [Nette\Application\Responses\FileResponse](https://api.nette.org/Nette.Application.Responses.FileResponse.html) for download file.

If you use in presenter
```php
/* @var $download h4kuna\Upload\Download */

// this create from data whose stored in database
$file = new File(...); // instance of IStoreFile
$presenter->sendResponse($download->createFileResponse($file));
```

Or if you use own script
```php
$file = new File(...); // instance of IStoreFile

try {
	$download->send($file);
} catch (\h4kuna\Upload\FileDownloadFailedException $e) {
	$myResponse->setCode(Nette\Http\IResponse::S404_NOT_FOUND);
}
exit;
```

Ftp
----
Ftp client is [dg/ftp-php](//github.com/dg/ftp-php) but not contained in this composer. Wrapper is [Ftp](src/Driver/Ftp.php).
```neon
uploadExtension:
    ftp:
        test:
            host: example.com
            user: root
            password: 123456
            url: http://example.com/images
            # optional
            path: images
            port: 21
            passive: true
```
This create services
- **uploadExtension.ftp.test** client \Ftp
- **uploadExtension.driver.test** IDriver
- **uploadExtension.upload.test** Upload with this driver
- **uploadExtension.download.test** Download with this driver

Own Driver service
-----------
This library save files only on filesystem and ftp. If you need to save on remote server via ssh etc. For this moment is not implemented, but it is prepared. Let's inspire on [LocalFilesystem](src/Driver/LocalFilesystem.php) 

- Create your own class with [IDriver](src/IDriver.php) interface
```php
<?php

class SshDriver implements \h4kuna\Upload\IDriver
{
// your implementation
} 
```
- Register in neon if you need
```neon
services:
    mySshDriver: SshDriver
    
    uploadComponent: UploadControl(@uploadExtension.upload.sshDriver)
    reponseComponent: DownloadControl(@uploadExtension.download.sshDriver)
    
uploadExtension:
	destinations:
		public: %wwwDir%/upload # first is default  
		sshDriver: @mySshDriver  
```
