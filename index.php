<?php
    include "storage.php";
    session_start();
    $polldb = new Storage(new JsonIO("polls.json"));
    $expdb = new Storage(new JsonIO("expire.json"));
    $polls = $polldb->findAll();
    foreach ($polls as $p) {
        if(strtotime(date('Y-m-d')) > strtotime($p['end'])){
            $expdb->update($p['id'],$p);
            $polldb->delete($p['id']);
        }
    }
    $polls = $polldb->findAll();
    usort($polls,function($b,$a){
        return strtotime($a['start']) - strtotime($b['start']);
    });
    $expires = $expdb->findAll();
    usort($expires,function($b,$a){
        return strtotime($a['start']) - strtotime($b['start']);
    });

    $userdb = new Storage(new JsonIO("users.json"));
    $users = $userdb->findAll();
    $user = [];
    $isLogged = false;
    if(isset($_SESSION['user'])){
        $isLogged = true;
        $user = $userdb->findById($_SESSION['user']);
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
            <a href="login.php" class="">Login</a>
            <a href="register.php" class="">Register</a>
        <?php endif; ?>

        <?php if(isset($_SESSION['user'])): ?>
            <a href="logout.php">LogOut</a>
        <?php endif; ?>
        
        <?php if($user && $user['isAdmin']): ?>
            <a href="pollmake.php">Create</a>
            <a href="pollview.php">Edit/Delete</a>
        <?php endif; ?>
      </nav>
    </header>

    <h1>Controversies Collected</h1>
    <div id="info">
        <p>
            We make individual ideas become community decisions. From simple things as choosing a meal to elaborate voting for a PM, this is the right place.
        </p>
    </div>
    <?php if(count($polls)>0): ?>
    <h2>Ongoing</h2>
    <div class="poll-table">
        <ul>
            <?php foreach($polls as $poll): ?>
                <li>
                    <div class="poll">
                        <div><?= $poll['id']?>-<?= $poll['title']?></div>
                        <div><?= $poll['start'] ?> TO <?= $poll['end'] ?></div>
                        <?php 
                            $caption = in_array($user['username'],$poll['voted']) ? "Edit" : "Vote";
                        ?>
                        <div><input type="button" value=<?= $caption ?> onclick='Redirect(<?= alphaTostr($poll["id"]) ?>)'></div>
                    </div>
                </li>    
            <?php endforeach; ?>
        </ul>
    </div>
    <br><br>
    <?php endif;?>
    <h2>Ended</h2>
    <div class="poll-table">
        <ul>
            <?php foreach($expires as $poll): ?>
                <li>
                    <div class="poll">
                        <div><?= $poll['id']?>-<?= $poll['title']?></div>
                        <div><?= $poll['start'] ?> TO <?= $poll['end'] ?></div>
                        <div><input type="button" value="Show" onclick='Show(<?= alphaTostr($poll["id"]) ?>)'></div>
                    </div>
                </li>    
            <?php endforeach; ?>
        </ul>
    </div>

</body>
</html>