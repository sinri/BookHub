<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BookHub - LeDoc</title>
</head>
<body>
<h1>
    BookHub
    <small>
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
    </small>
</h1>
<hr>
<div>
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
<hr>
<div>
    <?php echo $markdown; ?>
</div>
<hr>
<div>
    All Right Reserved. Copyright 2019 Sinri Edogawa.
</div>
</body>
</html>