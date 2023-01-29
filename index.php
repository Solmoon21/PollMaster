<?php
    date_default_timezone_set('Europe/Budapest');
    include "storage.php";
    session_start();
    $polldb = new Storage(new JsonIO("polls.json"));
    $expdb = new Storage(new JsonIO("expire.json"));
    $polls = [];
    $polls = $polldb->findAll();
    foreach ($polls as $p) {
        if(strtotime("now") > strtotime($p['end'])){
            $expdb->update($p['id'],$p);
            $polldb->delete($p['id']);
        }
    }
    $polls = $polldb->findAll();
    usort($polls,function($b,$a){
        return $a['start'] - $b['start'];
    });
    $expires = $expdb->findAll();
    usort($expires,function($b,$a){
        return $a['start'] - $b['start'];
    });
    $userdb = new Storage(new JsonIO("users.json"));
    $users = $userdb->findAll();
    $user = [];
    $isLogged = false;
    if(isset($_SESSION['user'])){
        $isLogged = true;
        $user = $userdb->findById($_SESSION['user']);
        if(!$user['isAdmin']){
            foreach ($polls as $key => $value) {
                if($value['group'] != 'All' && !in_array($value['group'],$user['groups'])){
                    unset($polls[$key]);
                }
            }
        }
    }
    else{
        foreach ($polls as $key => $value) {
            if($value['group'] != 'All'){
                unset($polls[$key]);
            }
        }
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
    <title>Main</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function Redirect(pid){
            <?php if($isLogged): ?>
                window.location.href = "vote.php?id="+pid
            <?php endif; ?>
            <?php if(!$isLogged): ?>
                window.location.href = "login.php"
            <?php endif; ?>
        }

        function Show(pid){
            window.location.href = "show.php?id="+pid
        }
    </script>
</head>
<body>
    <header>
      <nav>
        <?php if(!$user): ?>
            <div class="navlink">
                <a href="login.php" class="">Login</a>
            </div>
            <div class="navlink">
                <a href="register.php" class="">Register</a>
            </div>
            
        <?php endif; ?>

        <?php if(isset($_SESSION['user'])): ?>
            <a href="logout.php">LogOut</a>
        <?php endif; ?>
        
        <?php if($user && $user['isAdmin']): ?>
            <a href="pollmake.php">Make Polls</a>
            <a href="pollview.php">Edit/Delete</a>
            <a href="group.php">Make Groups</a>
        <?php endif; ?>
      </nav>
    </header>

    <h1>Controversies Collected</h1>
    <div id="info">
        <p>
            We make individual ideas become community decisions. From simple things as choosing a meal to elaborate voting for a PM, this is the right place.
        </p>
    </div>
    
    <h2>Ongoing</h2>
    <div class="poll-table">
        <ul>
            <?php foreach($polls as $poll): ?>
                <li>
                    <div class="poll">
                        <div><?= $poll['num']?>-<?= $poll['title']?></div>
                        <div><?= date("Y-m-d h:i",$poll['start']) ?> TO <?= $poll['end']?></div>
                        <?php 
                            $caption = isset($_SESSION['user']) && in_array($user['username'],$poll['voted']) ? "Edit" : "Vote";
                        ?>
                        <div><input type="button" value=<?= $caption ?> onclick='Redirect(<?= alphaTostr($poll["id"]) ?>)'></div>
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
                        <div><input type="button" value="Show" onclick='Show(<?= alphaTostr($poll["id"]) ?>)'></div>
                    </div>
                </li>    
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>