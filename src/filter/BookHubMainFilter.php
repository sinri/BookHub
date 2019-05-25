<?php


namespace sinri\bookhub\filter;


use sinri\ark\web\ArkRequestFilter;

class BookHubMainFilter extends ArkRequestFilter
{

    /**
     * Check request data with $_REQUEST, $_SESSION, $_SERVER, etc.
     * And decide if the request should be accepted.
     * If return false, the request would be thrown.
     * You can pass anything into $preparedData, that controller might use it (not sure, by the realization)
     * @param $path
     * @param $method
     * @param $params
     * @param mixed $preparedData
     * @param int $responseCode
     * @param null|string $error
     * @return bool
     */
    public function shouldAcceptRequest($path, $method, $params, &$preparedData = null, &$responseCode = 200, &$error = null)
    {
        return true;
    }

    /**
     * Give filter a name for Error Report
     * @return string
     */
    public function filterTitle()
    {
        return "BookHubMainFilter";
    }
}