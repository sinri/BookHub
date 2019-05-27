<?php


namespace sinri\bookhub\controller;


use Parsedown;
use sinri\ark\core\ArkHelper;
use sinri\ark\web\implement\ArkWebController;
use sinri\bookhub\core\BookHubStoreItem;
use sinri\bookhub\core\BookHubUtils;

class Reader extends ArkWebController
{
    protected function _showPage($templateFile, $params = [], $httpCode = 200)
    {
        $params['root'] = Ark()->readConfig(['root'], "/");
        parent::_showPage(__DIR__ . '/../view/' . $templateFile, $params, $httpCode);
    }

    public function index()
    {
        $this->_showPage('index.php', []);
    }

    public function openPath($components)
    {
//        var_dump($components);
        if (count($components) <= 1) {
            if (ArkHelper::readTarget($components, [0], '') === '') {
                if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
                    $parts = explode('?', $_SERVER['REQUEST_URI']);
                    $hasTailSplash = (substr($parts[0], -1, 1) === '/');
                } else {
                    $hasTailSplash = (substr($_SERVER['REQUEST_URI'], -1, 1) === '/');
                }
                header("Location: " . ($hasTailSplash ? "" : "./read/") . "index.md");
                return;
            }
        }
        $utils = new BookHubUtils();
        $type = $utils->tellPathType($components, $path);
        switch ($type) {
            case BookHubStoreItem::TYPE_FOLDER:
//                var_dump($components);
                $list = $utils->readDir($components);
                $this->_showPage("book-ls.php", ['list' => $list, "path" => $components]);
                break;
            case BookHubStoreItem::TYPE_MARKDOWN:
                $contents = file_get_contents($path);
                $Parsedown = new Parsedown();
                echo $Parsedown->text($contents);
                break;
            default:
                $this->_showPage('404.php', ["path" => $components]);
        }
    }

    public function ls($components)
    {
        if (count($components) < 1 || $components[0] === '') {
            $components = [];
        }
        $utils = new BookHubUtils();
        $list = $utils->readDir($components);
        $this->_showPage("book-ls.php", ['list' => $list, "path" => $components]);
    }

    public function read($components)
    {
        $utils = new BookHubUtils();
        $type = $utils->tellPathType($components, $path);
        if ($type === BookHubStoreItem::TYPE_MARKDOWN) {
            $contents = file_get_contents($path);
            $Parsedown = new Parsedown();
            echo $Parsedown->text($contents);
        } else {
            $this->_showPage('404.php', ["path" => $components]);
        }
    }
}