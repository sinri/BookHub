<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>BookHub - LeDoc</title>
</head>
<body>
<h1>BookHub</h1>
<h2>Explore <?php echo "~/" . implode("/", $path); ?></h2>
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

</body>
</html>