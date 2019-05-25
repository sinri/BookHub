<?php

use sinri\ark\core\ArkHelper;

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set("Asia/Shanghai");

ArkHelper::registerAutoload('sinri\bookhub', __DIR__);

if (true) {
    $config = [];
    require_once __DIR__ . '/../config.php';
    Ark()->setConfig($config);

//    Ark()->logger("debug")->info("Here is the debug log!");
//    Ark()->logger("debug")->setIgnoreLevel(LogLevel::DEBUG);
}