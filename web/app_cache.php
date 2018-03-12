<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

use Symfony\Component\HttpKernel\HttpCache\Store;

$kernel = new AppKernel('cache', true);
$kernel->loadClassCache();

$store = new Store(__DIR__.'/../app/cache/http');
$kernel = new AppCache($kernel, $store);

Request::enableHttpMethodParameterOverride();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
