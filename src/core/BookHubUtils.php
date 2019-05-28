<?php


namespace sinri\bookhub\core;


use Exception;

class BookHubUtils
{
    public static function getPath($components = [])
    {
//        echo __METHOD__.'@'.__LINE__.PHP_EOL;
//        var_dump($components);
        foreach ($components as $component) {
            if (strpos($component, "/") !== false || $component === '..' || $component === '.') {
                throw new Exception("God is watching you...");
            }
        }
        return __DIR__ . '/../../books' . (empty($components) ? "" : "/" . implode("/", $components));
    }

    /**
     * @param array $components
     * @param null|string &$path
     * @return bool|string
     * @throws Exception
     */
    public function tellPathType($components = [], &$path = null)
    {
        $path = self::getPath($components);
        if (!empty($components) && $components[count($components) - 1] === 'index') {
            $path = realpath($path . '/../');
            return BookHubStoreItem::TYPE_INDEX;
        }
        if (!file_exists($path)) {
            echo __METHOD__ . '@' . __LINE__ . ' dead path: ' . $path . PHP_EOL;
            return false;
        }
        if (is_dir($path)) {
            return BookHubStoreItem::TYPE_FOLDER;
        }
        if (strpos($path, '.md') === strlen($path) - 3) {
            return BookHubStoreItem::TYPE_MARKDOWN;
        }
//        if (strpos($path, '.php') === strlen($path) - 4) {
//            return BookHubStoreItem::TYPE_PHP;
//        }
        return false;
    }

    /**
     * @param string[] $relativeFolderPath
     * @return BookHubStoreItem[]
     * @throws Exception
     */
    public function readDir($relativeFolderPath = [])
    {
//        var_dump($relativeFolderPath);
        $dir_path = self::getPath($relativeFolderPath);
//        echo "dir path:".PHP_EOL;
//        var_dump($dir_path);
        $dir = opendir($dir_path);
        $list = [];
        while ($object = readdir($dir)) {
            if ($object === '.' || $object === '..') continue;
//            echo __METHOD__.'@'.__LINE__.' object is '.$object.PHP_EOL;
            $instance = false;
            $full_path = $dir_path . '/' . $object;
            if (is_dir($full_path)) {
                // is dir
                $instance = BookHubStoreItem::createAsFolder($relativeFolderPath, $object);
            } else {
                // is file
                if (strpos($object, ".md") === strlen($object) - 3) {
                    $instance = BookHubStoreItem::createAsMarkdown($relativeFolderPath, $object);
                }
            }
            if (!$instance) {
                continue;
            }
            $list[] = $instance;
        }
        return $list;
    }

    /**
     * @param array $relativeFolderPath
     * @return bool|mixed|string
     * @throws Exception
     */
    public function getFolderTitle($relativeFolderPath = [])
    {
        $dir_path = self::getPath($relativeFolderPath);
        if (!is_dir($dir_path)) return false;
        $indexFilePath = $dir_path . '/index.md';
        if (file_exists($indexFilePath)) {
            $instance = BookHubStoreItem::createAsMarkdown($relativeFolderPath, "index.md");
            if ($instance) {

                return $instance->title;
            }
        }
        if (count($relativeFolderPath) === 0) {
            return "ROOT";
        }
        return $relativeFolderPath[count($relativeFolderPath) - 1];
    }

    /**
     * @param string[] $pathComponentsHere
     * @return string
     * @throws Exception
     */
    public function getAutoIndexMarkdownContentsForFolder($pathComponentsHere)
    {
        $items = $this->readDir($pathComponentsHere);

        usort($items, function ($a, $b) {
            return $a->name > $b->name;
        });

        $contents = "# Auto Index of " . $this->getFolderTitle($pathComponentsHere) . PHP_EOL . PHP_EOL;

//        if(!empty($pathComponentsHere)){
//            $contents.="* [Back to parent](..)".PHP_EOL;
//        }

        foreach ($items as $index => $item) {
            $contents .= "* [{$item->title}](./{$item->name})" . PHP_EOL;
        }

        return $contents;
    }
}