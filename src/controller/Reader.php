<?php


namespace sinri\bookhub\controller;


use Exception;
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

    protected function _showError($httpCode, $components, $error = null)
    {
        if ($error !== null) {
            switch ($httpCode) {
                case 404:
                    $error = "Your requested page is missing.";
                    break;
                case 500:
                    $error = "There is something wrong.";
                    break;
                default:
                    $error = "Unknown Mistake.";
                    break;
            }
        }
        $this->_showPage("error.php", ["path" => $components, "error" => $error], $httpCode);
    }

    public function index()
    {
        $this->_showPage('index.php', []);
    }

    protected function hasTailSplash()
    {
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
            $parts = explode('?', $_SERVER['REQUEST_URI']);
            $hasTailSplash = (substr($parts[0], -1, 1) === '/');
        } else {
            $hasTailSplash = (substr($_SERVER['REQUEST_URI'], -1, 1) === '/');
        }
        return $hasTailSplash;
    }

    /**
     * @param $components
     * @throws Exception
     * @deprecated
     */
    public function openPath($components)
    {
//        var_dump($components);
        if (count($components) <= 1) {
            if (ArkHelper::readTarget($components, [0], '') === '') {
                $hasTailSplash = $this->hasTailSplash();
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
                //$this->_showPage('error.php', ["path" => $components]);
                $this->_showError(500, $components);
        }
    }

    /**
     * @param $components
     * @throws Exception
     */
    public function ls($components)
    {
        if (count($components) < 1 || $components[0] === '') {
            $components = [];
        }
        $utils = new BookHubUtils();
        $list = $utils->readDir($components);
        $this->_showPage("book-ls.php", ['list' => $list, "path" => $components]);
    }

    /**
     * @param $components
     * @throws Exception
     */
    public function read($components)
    {
        $utils = new BookHubUtils();
        $type = $utils->tellPathType($components, $path);
        if ($type === BookHubStoreItem::TYPE_MARKDOWN) {
            $contents = file_get_contents($path);
            $Parsedown = new Parsedown();
            $markdown = $Parsedown->text($contents);
            $this->_showPage("book-cat.php", ['markdown' => $markdown, "path" => $components]);
        } elseif ($type === BookHubStoreItem::TYPE_PHP) {
            // auto index
            $contents = [];
            require $path;
            $Parsedown = new Parsedown();
            $markdown = $Parsedown->text($contents);
            $this->_showPage("book-cat.php", ['markdown' => $markdown, "path" => $components]);
        } else {
            $this->_showError(500, $components);
        }
    }

    public function x1($components)
    {
//        var_dump($components);

        if (count($components) < 1 || $components[0] === '') {
            // here is root
            $components = [];
        }

        $utils = new BookHubUtils();
        $type = $utils->tellPathType($components, $path);
//        var_dump($type);
        switch ($type) {
            case BookHubStoreItem::TYPE_FOLDER:
                $hasTailSplash = $this->hasTailSplash();
                // check if index.md
                if (file_exists($path . '/index.md')) {
                    header("Location: " . ($hasTailSplash ? "." : "../read" . (empty($components) ? "" : "/") . implode("/", $components)) . "/index.md");
                } else {
                    // show auto index
                    header("Location: " . ($hasTailSplash ? "." : "../read" . (empty($components) ? "" : "/") . implode("/", $components)) . "/index");
                }
                break;
            case BookHubStoreItem::TYPE_MARKDOWN:
                if (!file_exists($path)) {
                    $this->_showError(404, $components);
                } else {
                    $contents = file_get_contents($path);
                    $Parsedown = new Parsedown();
                    $markdown = $Parsedown->text($contents);
                    $this->_showPage("book-cat.php", ['markdown' => $markdown, "path" => $components, 'type' => $type]);
                }
                break;
            case BookHubStoreItem::TYPE_INDEX:
                // the auto index
                $folder = json_decode(json_encode($components), true);
                array_splice($folder, count($folder) - 1, 1);
                $contents = $utils->getAutoIndexMarkdownContentsForFolder($folder);
                $Parsedown = new Parsedown();
                $markdown = $Parsedown->text($contents);
                $this->_showPage("book-cat.php", ['markdown' => $markdown, "path" => $components, 'type' => $type]);
                break;
            default:
                $this->_showError(500, $components);
                break;
        }
    }
}