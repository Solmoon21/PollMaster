<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header("location:index.php");
        exit();
    }

    include "storage.php";
    $polldb = new Storage(new JsonIO("polls.json"));
    $p = $polldb->findById($_GET['id']);
    $userdb = new Storage(new JsonIO("users.json"));
    $u = $userdb->findById($_SESSION['user']);
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
    <h1><?= $p['id'] ?>-<?= $p['title'] ?></h1>
    <form method="POST" action="voted.php">
    <input type="hidden" name="id" value=<?=$p['id']?> />
        <?php if($p['isMultiple']): ?>
            <?php foreach($p['options'] as $option): ?>
                <input type="checkbox" name=<?= $option ?>><?= $option ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if(!$p['isMultiple']): ?>
            <?php foreach($p['options'] as $option): ?>
                <input type="radio" name="answer" value=<?= $option?>><?= $option ?>
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
        <input type="submit" value="submit">
    </form>
    
    <div>
        Votes are accepted until <?= $p['end'] ?>
    </div>
    <div>
        Created since <?= $p['start'] ?>
    </div>
</body>
</html>