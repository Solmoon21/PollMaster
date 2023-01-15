<?php
    include "poll.php";
    $p = new Poll(1,"Title",["A1","A2","A3"],"Today","Tomorrow",false);
    $data = [];
    $text = "__PlaceHolder__";
    $succ = true;

    function validate($post,&$data,$p){
        $data = $post;
        if(!isset($data['answer'])){
            foreach ($p->options as $o) {
                if(isset($data[$o])){
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    $text = ($succ = validate($_POST,$data,$p)) ? "Your vote has been recorded" : "An Answer must be selected";

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
    <button onclick="location.href='vote.php'">Change Vote</button>
    <button onclick="location.href='index.php'">Home</button>
</body>
</html>