<?php


namespace sinri\bookhub\core;


class BookHubUtils
{

    public static function getLogger($name = 'BookHub')
    {
        return Ark()->logger($name);
    }
}