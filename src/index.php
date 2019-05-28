<?php

use sinri\ark\io\ArkWebOutput;
use sinri\ark\web\ArkRouteErrorHandler;
use sinri\bookhub\controller\Reader;
use sinri\bookhub\filter\BookHubMainFilter;

require_once __DIR__ . '/autoload.php';

$arkWebService = Ark()->webService();
$router = $arkWebService->getRouter();
$logger = Ark()->logger("web");

$arkWebService->setDebug(true);
$arkWebService->setLogger($logger);
$router->setDebug(true);
$router->setLogger($logger);

//$logger->debug(__FILE__ . '@' . __LINE__);

$router->setErrorHandler(
    ArkRouteErrorHandler::buildWithCallback(
        function ($error_message, $status_code) use ($logger) {
            $detail = ['error' => $error_message, 'code' => $status_code];
            $logger->warning(__METHOD__ . '@' . __LINE__, $detail);
            Ark()->webOutput()->jsonForAjax(ArkWebOutput::AJAX_JSON_CODE_FAIL, $detail);
        }
    )
)
    ->loadAllControllersInDirectoryAsCI(
        __DIR__ . '/controller',
        'api/',
        '\sinri\bookhub\controller\\',
        [BookHubMainFilter::class]
    )
//    ->get(
//    "read",
//    [Reader::class, 'read'],
//    [BookHubMainFilter::class],
//    true
//)
//    ->get(
//    "ls",
//    [Reader::class, 'ls'],
//    [BookHubMainFilter::class],
//    true
//)
    ->get(
        "read",
        [Reader::class, 'x1'],
        [BookHubMainFilter::class],
        true
    )
    ->get("", function () {
//    echo "Welcome to BookHub!" . PHP_EOL;
        header("Location: ./read/index.php");
    });


$arkWebService->handleRequestForWeb();