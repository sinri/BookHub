<?php

use sinri\ark\io\ArkWebOutput;
use sinri\ark\web\ArkRouteErrorHandler;
use sinri\bookhub\filter\BookHubMainFilter;

require_once __DIR__ . '/autoload.php';

$arkWebService = Ark()->webService();
$arkWebService->getRouter()
    ->handleRouteError(
        ArkRouteErrorHandler::buildWithCallback(
            function ($error_message, $status_code) {
                Ark()->webOutput()->jsonForAjax(
                    ArkWebOutput::AJAX_JSON_CODE_FAIL,
                    ['error' => $error_message, 'code' => $status_code]
                );
            }
        )
    );

$arkWebService->getRouter()
    ->loadAllControllersInDirectoryAsCI(
        __DIR__ . '/controller',
        'api/',
        '\sinri\bookhub\controller\\',
        [BookHubMainFilter::class]
    );

$arkWebService->getRouter()
    ->get("", function () {
        echo "Welcome to BookHub!" . PHP_EOL;
    });

$arkWebService->handleRequestForWeb();