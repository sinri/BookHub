<?php


namespace sinri\bookhub\core;


use Parsedown;

class BookHubStoreItem
{
    const TYPE_FOLDER = "FOLDER";
    const TYPE_MARKDOWN = "MARKDOWN";
    const TYPE_PHP = "PHP";
    const TYPE_INDEX = "INDEX";
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $fullPath;
    /**
     * @var string[]
     */
    public $fullPathComponents;
    /**
     * @var string
     */
    public $title;

    /**
     * @param $parentPathComponents
     * @param $folderName
     * @return bool|BookHubStoreItem
     * @throws \Exception
     */
    public static function createAsFolder($parentPathComponents, $folderName)
    {
        $instance = new BookHubStoreItem();
        $instance->type = self::TYPE_FOLDER;
        $instance->name = $folderName;
        $instance->fullPath = BookHubUtils::getPath($parentPathComponents) . '/' . $folderName;

        if (!is_dir($instance->fullPath)) return false;

        $instance->fullPathComponents = json_decode(json_encode($parentPathComponents), true);
        $instance->fullPathComponents[] = $folderName;

//        $indexPath = $instance->fullPath . '/index.md';
//        if (!file_exists($indexPath) || !is_file($indexPath) || !is_readable($indexPath)) return false;
//        $file = fopen($indexPath, 'r');
//        if (!$file) return false;
//        $line = fgets($file);
//        fclose($file);
//        if ($line === false) return false;
//        $replaced = preg_replace('/^#+\s+/', "", $line);
//        $replaced = preg_replace('/\s+$/', "", $replaced);
//        if (strlen($replaced) === 0) return false;
//        $instance->title = $replaced;

        $instance->title = self::getTitleRowFromFile($instance->fullPath . '/index.md');
        if ($instance->title === false) return false;

        return $instance;
    }

    /**
     * @param $parentPathComponents
     * @param $fileName
     * @return bool|BookHubStoreItem
     * @throws \Exception
     */
    public static function createAsMarkdown($parentPathComponents, $fileName)
    {
        $instance = new BookHubStoreItem();
        $instance->type = self::TYPE_MARKDOWN;
        $instance->name = $fileName;
        $instance->fullPath = BookHubUtils::getPath($parentPathComponents) . '/' . $fileName;
        $instance->fullPathComponents = json_decode(json_encode($parentPathComponents), true);
        $instance->fullPathComponents[] = $fileName;

        /*
        if (!file_exists($instance->fullPath) || !is_file($instance->fullPath) || !is_readable($instance->fullPath)) return false;
        $file = fopen($instance->fullPath, 'r');
        if (!$file) return false;
        $line = fgets($file);
        fclose($file);
        if ($line === false) return false;
        $replaced = preg_replace('/^#+\s+/', "", $line);
        $replaced = preg_replace('/\s+$/', "", $replaced);
        if (strlen($replaced) === 0) return false;
        $instance->title = $replaced;
        */
        $instance->title = self::getTitleRowFromFile($instance->fullPath);
        if ($instance->title === false) return false;

        return $instance;
    }

    public function getRawMarkdownContents()
    {
        $contents = file_get_contents($this->fullPath);
        return $contents;
    }

    public function getParsedHtmlContents()
    {
        $Parsedown = new Parsedown();
        return $Parsedown->text($this->getRawMarkdownContents());
    }

    protected static function getTitleRowFromFile($filePath)
    {
        $file = fopen($filePath, 'r');
        if (!$file) return false;
        $line = fgets($file);
        fclose($file);
        if ($line === false) return false;
        $replaced = preg_replace('/^#+\s+/', "", $line);
        $replaced = preg_replace('/\s+$/', "", $replaced);
        if (strlen($replaced) === 0) return false;
        return $replaced;
    }
}