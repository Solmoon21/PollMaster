<?php
    if(!isset($_GET['id'])){
        header("location:index.php");
        exit();
    }

    include "storage.php";
    $db = new Storage(new JsonIO("expire.json"));
    $p = $db->findById($_GET['id']);
    if(!$p){
        header("location:index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="index.php">Main</a>
    <h1> <?= $p['num'] ?> <?= $p['title'] ?> </h1>
    <ul>
        <?php foreach($p['answers'] as $key=>$value): ?>
            
            <li>
                <?php 
                    echo count($p['answers'][$key])." votes for ".$key;
                ?>
            </li>

        <?php endforeach ?>
    </ul>
</body>
</html>