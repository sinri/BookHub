<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BookHub - LeDoc</title>
</head>
<body>
<h1>
    BookHub
    <small>Explore <?php echo "~/" . implode("/", $path); ?></small>
</h1>
<hr>
<!--<div><pre>--><?php //var_dump($list); ?><!--</pre></div>-->
<div>
    <ul>
        <?php if (!empty($path)) { ?>
            <li>
                📂 <a href=".">Back to parent folder</a>
            </li>
            <?php
        }
        foreach ($list as $item) {
//            var_dump($item);
            if ($item->type === 'MARKDOWN') {
                ?>
                <li>
                    📄 <a href="<?php echo $root; ?>read/<?php echo implode("/", $item->fullPathComponents); ?>"
                          target="_blank"><?php echo $item->title; ?></a>
                </li>
                <?php
            } else {
                ?>
                <li>
                    📁
                    <a href="<?php echo $root; ?>ls/<?php echo implode("/", $item->fullPathComponents); ?>"><?php echo $item->title; ?></a>
                </li>
                <?php
            }
        }
        ?>
    </ul>
</div>
<hr>
<div>
    All Right Reserved. Copyright 2019 Sinri Edogawa.
</div>
</body>
</html>