<?php
    $users = [];

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

        $users = $userdb->findAll();
    }

    $data = [];
    $error= [];
    $succ = false;
    $checked = false;
    function validate($post,&$data,&$error){
        global $users,$checked;
        $data = $post;
        if(count($data)==0){
            return false;
        }

        if(isset($post['groupname'])){
            $old = false;
            if(empty($post['groupname'])){
                $error['groupname'] = "Groupname can't be empty";
            }
            else{
                $userdb = new Storage(new JsonIO("users.json"));
                foreach ($userdb->findAll() as $u) {
                    if(in_array($post['groupname'],$u['groups'])){
                        $old = true;
                    }
                }
            }
            
            if(!$old){
                $count = 0;
                foreach ($users as $u) {
                    if(!isset($post[$u['username']])){
                        $count++;
                    }
                }
                if($count == count($users)){
                    $error['users'] = "Must include at least one user";
                }
            }
        }

        $checked = true;
        return count($error) == 0;
    }

    $succ = validate($_POST,$data,$error);
    $text = $succ ? "Group Created" : "Error creating group";
    if($succ){
        $userdb = new Storage(new JsonIO("users.json"));
        foreach ($userdb->findAll() as $u) {
            if(isset($_POST[$u['username']])){
                if(!in_array($_POST['groupname'],$u['groups']))
                    $u['groups'][] = $_POST['groupname'];
            }
            else{
                $key = array_search($_POST['groupname'], $u['groups']);
                if (false !== $key) {
                    unset($u['groups'][$key]);
                }
            }
            $userdb->update($u['id'],$u);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Groups</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="" method="post">
        <label for="Groupname">Groupname</label>
        <input type="text" name="groupname" id="Groupname" value=<?= $_POST['groupname']??""?>>
        <?php 
            if (isset($error['groupname'])){
                echo "<span class='error'>";
                echo $error['groupname'];
                echo "</span>";
            }
        ?>
        <br><br>
        <?php foreach ($users as $user):?>
            <?php if(!$user['isAdmin']): ?>
                <?= $user['username'] ?>
                <input type="checkbox" name=<?= $user['username'] ?> id="" <?=  isset($_POST[$user['username']])? "checked" : "" ?>    >
            <?php endif; ?>
        <?php endforeach; ?>
        <?php 
            if (isset($error['users'])){
                echo "<span class='error'>";
                echo $error['users'];
                echo "</span>";
            }
        ?>
        <br><br>
        <input type="submit" value="Create">
    </form>

    <?php if($checked): ?>
        <h2 class=<?= $succ?"success":"error" ?> ><?= $text ?></h2>    
    <?php endif; ?>

</body>
</html>