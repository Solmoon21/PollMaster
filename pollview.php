<?php
    date_default_timezone_set('Europe/Budapest');
    include "storage.php";
    session_start();
    if(!isset($_SESSION['user']))
    {
        header("location:login.php");
        exit();
    }
    else{
        $userdb = new Storage(new JsonIO("users.json"));
        $u = $userdb->FindById($_SESSION['user']);
        if(!$u['isAdmin']){
            header("location:login.php");
            exit();
        } 
    }
    $polldb = new Storage(new JsonIO("polls.json"));
    $expdb = new Storage(new JsonIO("expire.json"));
    $polls = $polldb->findAll();
    usort($polls,function($b,$a){
        return strtotime($a['start']) - strtotime($b['start']);
    });
    $expires = $expdb->findAll();
    usort($expires,function($b,$a){
        return strtotime($a['start']) - strtotime($b['start']);
    });


    if(isset($_GET['deleteid'])){
        if($polldb->findById($_GET['deleteid'])){
            $polldb->delete($_GET['deleteid']);
        }
        else{
            $expdb->delete($_GET['deleteid']);
        }
        header("location:pollview.php");
        exit();
    }
    
    function alphaTostr($alpha){
        $f = '"';
        return $f.$alpha.$f;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <script>

        function Edit(pid){
            window.location.href = "pollmake.php?id="+pid
        }

        function Delete(pid){
            window.location.href = "pollview.php?deleteid="+pid
        }

    </script>
</head>
<body>
    <a href="index.php">Main</a>
    <h2>Ongoing</h2>
    <div class="poll-table">
        <ul>
            <?php foreach($polls as $poll): ?>
                <li>
                    <div class="admin-poll">
                        <div><?= $poll['num']?>-<?= $poll['title']?></div>
                        <div><?= date("Y-m-d h:i",$poll['start']) ?> TO <?= $poll['end'] ?></div>
                        <div><input type="button" value="Edit" onclick='Edit(<?= alphaTostr($poll["id"]) ?>)'></div>
                        <div><input type="button" value="Delete" onclick='Delete(<?= alphaTostr($poll["id"]) ?>)'></div>
                    </div>
                </li>    
            <?php endforeach; ?>
        </ul>
    </div>
    <br><br>
    <h2>Ended</h2>
    <div class="poll-table">
        <ul>
            <?php foreach($expires as $poll): ?>
                <li>
                    <div class="poll">
                        <div><?= $poll['num']?>-<?= $poll['title']?></div>
                        <div><?= date("Y-m-d h:i",$poll['start']) ?> TO <?= $poll['end'] ?></div>
                        <div><input type="button" value="Delete" onclick='Delete(<?= alphaTostr($poll["id"]) ?>)'></div>
                    </div>
                </li>    
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>