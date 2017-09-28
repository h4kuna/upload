<?php

namespace h4kuna\Upload\Upload;

use Tester\Assert;

include __DIR__ . '/../../bootsrap.php';

// allow all
$uploadFilter = new Filter();
Assert::true($uploadFilter->isAllowed('foo'));

// content type
$uploadFilter = new Filter('application/json', 'plain/text');
Assert::true($uploadFilter->isAllowed('application/json'));
Assert::true($uploadFilter->isAllowed('PLAIN/TEXT'));
Assert::false($uploadFilter->isAllowed('foo/bar'));