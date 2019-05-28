<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - <?php echo $type; ?> - BookHub - LeDoc</title>
    <link rel="stylesheet" href="https://unpkg.com/github-markdown-css@2.10.0/github-markdown.css">
</head>
<body style="background: lightgray">
<!--<hr>-->
<div style="margin: 5px auto;padding:10px;background: lightblue">
    <span>BookHub</span>
    &nbsp;&nbsp;
    |
    &nbsp;&nbsp;
    <span style="font-size: 14px">
        <?php
        switch ($type) {
            case \sinri\bookhub\core\BookHubStoreItem::TYPE_INDEX:
                echo "Auto Index of ~/" . implode("/", array_slice($path, 0, count($path) - 1));
                break;
            case \sinri\bookhub\core\BookHubStoreItem::TYPE_MARKDOWN:
            default:
                echo "Read ~/" . implode("/", $path);
                break;
        }
        ?>
    </span>
    &nbsp;&nbsp;
    |
    &nbsp;&nbsp;
    Quick Access:
    &nbsp;&nbsp;
    <?php if ($type === \sinri\bookhub\core\BookHubStoreItem::TYPE_INDEX) { ?>
        <a href="./index.md">🔖 Abstract</a>
    <?php } else { ?>
        <a href="./index">🏷 Index</a>
    <?php } ?>
    &nbsp;&nbsp;
    <?php if (count($path) !== 1 || ($path[0] !== 'index' && $path[0] !== 'index.md')) { ?>
        <!--        <a href="../index">⬆️🏷 Parent Auto Index</a>-->
        <!--        <a href="../index.md">⬆️🔖 Parent Abstract Page</a>-->
        <a href="../">⬆️ Parent</a>
    <?php } ?>
</div>
<!--<hr>-->
<div style="background: white;padding: 10px">
    <div class="markdown-body">
        <?php echo $markdown; ?>
    </div>
</div>
<hr>
<div>
    All Right Reserved. Copyright 2019 Sinri Edogawa.
</div>
</body>
</html>