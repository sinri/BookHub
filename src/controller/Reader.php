<?php


namespace sinri\bookhub\controller;


use Exception;
use Parsedown;
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
        if ($error === null) {
            switch ($httpCode) {
                case 404:
                    $error = "[404] Your requested page is missing.";
                    break;
                case 500:
                    $error = "[500] There is something wrong.";
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
     */
    public function read($components)
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
//                $hasTailSplash = $this->hasTailSplash();
                // check if index.md
                $root = Ark()->readConfig(['root'], "./");
                if (file_exists($path . '/index.md')) {
                    header("Location: {$root}read/" . (empty($components) ? "" : implode("/", $components) . "/") . "index.md");
//                    header("Location: " . ($hasTailSplash ? "." : "../read" . (empty($components) ? "" : "/") . implode("/", $components)) . "/index.md");
                } else {
                    // show auto index
                    header("Location: {$root}read/" . (empty($components) ? "" : implode("/", $components) . "/") . "index");
//                    header("Location: " . ($hasTailSplash ? "." : "../read" . (empty($components) ? "" : "/") . implode("/", $components)) . "/index");
                }
                break;
            case BookHubStoreItem::TYPE_MARKDOWN:
                if (!file_exists($path)) {
                    $this->_showError(404, $components);
                } else {
                    $item = BookHubStoreItem::createAsMarkdown(array_slice($components, 0, count($components) - 1), $components[count($components) - 1]);

//                    $contents = file_get_contents($path);
//                    $Parsedown = new Parsedown();
//                    $markdown = $Parsedown->text($contents);
                    $this->_showPage(
                        "book-cat.php",
                        [
                            'markdown' => $item->getParsedHtmlContents(),
                            "path" => $components,
                            'type' => $type,
                            'title' => $item->title,
                        ]
                    );
                }
                break;
            case BookHubStoreItem::TYPE_INDEX:
                // the auto index
                if (count($components) <= 1) {
                    $title = "ROOT";
                } else {
                    $item = BookHubStoreItem::createAsFolder(array_slice($components, 0, count($components) - 2), $components[count($components) - 2]);
                    $title = $item->title;
                }
                $folder = json_decode(json_encode($components), true);
                array_splice($folder, count($folder) - 1, 1);
                $contents = $utils->getAutoIndexMarkdownContentsForFolder($folder);
                $Parsedown = new Parsedown();
                $markdown = $Parsedown->text($contents);
                $this->_showPage(
                    "book-cat.php",
                    [
                        'markdown' => $markdown,
                        "path" => $components,
                        'type' => $type,
                        'title' => $title,
                    ]
                );
                break;
            default:
                $this->_showError(500, $components, "The Type Is Not Processable.");
                break;
        }
    }
}