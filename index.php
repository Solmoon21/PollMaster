<?php
    include "poll.php";
    
    $p = new Poll(1,"Title",["o1","o2"],"Today","Tomorrow",false);
    $q = new Poll(2,"Title",["o1","o2"],"Today","Tomorrow",false);
    $polls[] = $p;
    $polls[] = $q;
    $polls[] = $p;
    $polls[] = $q;
    $polls[] = $q;
    $polls[] = $p;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Controversies Collected</h1>
    <div id="info">
        <p>
            We make individual ideas become community decisions. From simple things as choosing a meal to elaborate voting for a PM, this is the right place.
        </p>
    </div>
    
    <div class="poll-table">
        <ul>
            <?php foreach($polls as $poll): ?>
                <li>
                    <div class="poll">
                        <div><?= $poll->id ?>-<?= $poll->title?></div>
                        <div><?= $poll->start ?> TO <?= $poll->end ?></div>
                        <div><input type="button" value="Vote" onclick="window.location='vote.php?id=<?= $poll->id ?>'"></div>
                    </div>
                </li>    
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="poll-table">

    </div>

</body>
</html>