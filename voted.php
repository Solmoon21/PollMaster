<?php
    session_start();

    include "storage.php";
    $polldb = new Storage(new JsonIO("polls.json"));
    $userdb = new Storage(new JsonIO("users.json"));
    $p = $polldb->findById($_POST['id']);
    $u = $userdb->findById($_SESSION['user']);
    $data = [];
    $text = "__PlaceHolder__";
    $succ = true;

    function validate($post,&$data,$p){
        $data = $post;
        if(!isset($data['answer'])){
            foreach ($p['options'] as $o) {
                if(isset($data[$o])){
                    return true;
                }
            }
            return false;
        }
        return true;
    }
    $succ = validate($_POST,$data,$p);
    if($succ){
        $p['voted'][] = $u['username'];
        foreach ($p['options'] as $o) {
            if(isset($_POST[$o]))
                $p['answers'][$o]++;
            if(isset($_POST['answer']) && $_POST['answer']==$o){
                $p['answers'][$o]++;
            }
        }
        
        $polldb->update($p['id'],$p);
        $text = "Your vote has been recorded";
    }else{
        $text = "An Answer must be selected";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CC.COM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 style="text-align:center" class= <?= $succ?"success":"error" ?>> <?=$text?>  </h1>
    <button onclick="location.href='vote.php?id=<?=$p['id']?>'">Change Vote</button>
    <button onclick="location.href='index.php'">Home</button>
</body>
</html>