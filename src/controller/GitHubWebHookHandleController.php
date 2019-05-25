<?php


namespace sinri\bookhub\controller;


use sinri\ark\web\implement\ArkWebController;

class GitHubWebHookHandleController extends ArkWebController
{
    protected $logger;

    public function __construct()
    {
        parent::__construct();
        $this->logger = Ark()->logger('GitHubWebHook');
    }

    public function receiveGitHubCalling()
    {
        $raw = $this->_getInputHandler()->getRawPostBody();
        $json = $this->_getInputHandler()->getRawPostBodyParsedAsJson();

        $this->logger->info("Incoming WebHook Event");
        $this->logger->logInline($raw);
        $this->logger->info("PARSED AS JSON", ['body' => $json]);
    }
}