<?php
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
    $data = [];
    $error = [];
    $succ = false;
    $opts = "";

    $polldb = new Storage(new JsonIO("polls.json"));
    $userdb = new Storage(new JsonIO("users.json"));
    $users = $userdb->findAll();
    $groups = [];
    foreach($users as $u){
       $groups = array_merge($groups,$u['groups']);
    }
    $groups[] = "All";
    $groups = array_unique($groups);

    function validate($get,&$data,&$error){
        $data = $get;
        if(count($data)<=1 )
            return False;

        if(count($data)>1){
            if(!isset($get['isMultiple']) ){
                $error['isMultiple'] = "Type must be given";
            }
        }

        if(isset($get['title'])){
            if(empty($get['title'])){
                $error['title'] = "Title can't be empty";  
            }
        }

        if(isset($get['options'])){
            if(empty($get['options'])){
                $error['options'] = "Options can't be empty";
            }
        }

        if(isset($get['end'])){
            if(empty($get['end'])){
                $error['end'] = "Deadline must be given";
            }
            else if(isset($get['start'])){
                
                if(strtotime($get['end'])<strtotime($get['start']))
                    $error['end'] = "Time Travel?";
            }
            else{
                $date = strtotime($get['end']);
                $data['end'] = date('n/j/Y',strtotime($get['end']));
            }
        }

        return count($error) == 0;
    }
    
    $editpoll = [];
    if(isset($_GET['id'])){
        $editpoll = $polldb->findById($_GET['id']);
    }
    
    $succ = validate($_GET,$data,$error);
    
    
    $text = count($data)<=1 ? "" : ($succ ? "Your poll has been created" : "Your poll has missing attributes");
    if($succ){
        $data['options'] = array_filter(explode("\r\n",$data['options']));
        $data['voted'] = [];
        $data['users'] = [];
        foreach($data['options'] as $o){
            $data['answers'][$o] = 0;
        }
        if(!isset($_GET['id'])){
            //$data['start'] = date('Y-m-d H-i-s',strtotime(urldecode($data['start'])));
            $data['start'] = strtotime("now");
            $polldb->add($data);
        }
        else{
            $data['start'] = $editpoll['start'];
            $polldb->update($_GET['id'],$data);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Poll</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="index.php">Main</a><br><br>
    <form id="f" action="" method="get">
        <label for="Title">Title:</label> <input type="text" name="title" id="Title" value=<?= $_GET['title']?? ($editpoll['title'] ?? "")?>>
        <?php 
            if (isset($error['title'])){
                echo "<span class='error'>";
                echo $error['title'];
                echo "</span>";
            }
        ?>
        <br>
        <br>
        <label for="Options">Options:</label> 
        <textarea type="text" name="options" id="Options" rows="5" cols="80" ></textarea>
        <?php 
            if (isset($error['options'])){
                echo "<span class='error'>";
                echo $error['options'];
                echo "</span>";
            }
        ?>
        <br><br>
        <label>Are multiple answers allowed?</label>
        <?php 
            if (isset($error['isMultiple'])){
                echo "<span class='error'>";
                echo $error['isMultiple'];
                echo "</span>";
            }
        ?>
        <br><br>
        <label for="Yes">Yes</label> 
        <input type="radio" name="isMultiple" id="Yes" value=1 <?php if((isset($_GET['isMultiple'])&&$_GET['isMultiple']=="1") || (isset($editpoll['isMultiple'])&&$editpoll['isMultiple']=="1")) echo "checked = checked"; ?>  >
        <label for="No">No</label> 
            <input type="radio" name="isMultiple" id="No" value=0 <?php if((isset($_GET['isMultiple'])&&$_GET['isMultiple']=="0") || (isset($editpoll['isMultiple'])&&$editpoll['isMultiple']=="0")) echo "checked = checked"; ?>>
        <br><br>
        <label for="End">Deadline:</label>
        <input type="date" name="end" id="End" value= <?= $editpoll['end'] ?? ""?> >
        <?php 
            if (isset($error['end'])){
                echo "<span class='error'>";
                echo $error['end'];
                echo "</span>";
            }
        ?>
        <br><br>
        <label for="Group">Select Group</label>
        <select name="group" id="Group">
            <?php foreach($groups as $g): ?>
                <option value=<?=$g?>><?=$g?></option>
            <?php endforeach; ?>
        </select>

        <input name="start" type="hidden">
        <?php if(isset($_GET['id'])): ?>
            <input name="id" type="hidden" value=<?= $_GET['id'] ?>>
        <?php endif; ?>
        <button type="submit"><?= isset($_GET['id'])?"Edit":"Create"?></button>
    </form>
    

    <?php if(!empty($text)): ?>
        <h1 style="text-align:center" class= <?= $succ?"success":"error" ?>> <?=$text?>  </h1>
    <?php endif; ?>

</body>
</html>