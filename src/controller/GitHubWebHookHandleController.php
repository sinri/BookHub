<?php


namespace sinri\bookhub\controller;


use sinri\ark\web\implement\ArkWebController;

class GitHubWebHookHandleController extends ArkWebController
{
    protected $logger;
    protected $webHookSecret;

    public function __construct()
    {
        parent::__construct();
        $this->logger = Ark()->logger('GitHubWebHook');
        $this->webHookSecret = "BookHub";
    }

    public function receiveGitHubCalling()
    {
        //$raw = $this->_getInputHandler()->getRawPostBody();
        //$json = $this->_getInputHandler()->getRawPostBodyParsedAsJson();

        $written = file_put_contents(__DIR__ . '/../../runtime/push_hook', time());

        $this->logger->info("Incoming WebHook Event!", ['written' => $written]);
        //$this->logger->logInline($raw);
        //$this->logger->info("PARSED AS JSON", ['body' => $json]);

        exec("pwd", $output);

        $this->logger->info("call exec", ["output" => $output]);

        $this->_sayOK($output);
    }
}