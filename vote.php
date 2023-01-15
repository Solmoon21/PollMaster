<?php
    include "poll.php";
    $p = new Poll(1,"Title",["A1","A2","A3"],"Today","Tomorrow",false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- $p = Storage[$_GET[id]]  -->
    <h1><?= $p->id ?>-<?= $p->title ?></h1>
    <form method="POST" action="voted.php">
    <input type="hidden" name="id" value=<?=$p->id?> />
        <?php if($p->isMultiple): ?>
            <?php foreach($p->options as $option): ?>
                <input type="checkbox" name=<?= $options ?>><?= $option ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if(!$p->isMultiple): ?>
            <?php foreach($p->options as $option): ?>
                <input type="radio" name="answer" value=<?= $option?>><?= $option ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
        <input type="submit" value="submit">
        <!-- handle data storage in voted.php -->
    </form>
    
    <div>
        Votes are accepted until <?= $p->end ?>
    </div>
    <div>
        Created since <?= $p->start ?>
    </div>
</body>
</html>