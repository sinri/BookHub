<?php
require_once __DIR__ . '/../vendor/autoload.php';

if (true) {
    $config = [];
    require_once __DIR__ . '/../config.php';
    Ark()->setConfig($config);
}