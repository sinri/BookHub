<?php

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set("Asia/Shanghai");

if (true) {
    $config = [];
    require_once __DIR__ . '/../config.php';
    Ark()->setConfig($config);

//    Ark()->logger("debug")->info("Here is the debug log!");
//    Ark()->logger("debug")->setIgnoreLevel(LogLevel::DEBUG);
}