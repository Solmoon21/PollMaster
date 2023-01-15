<?php
    $error = [];
    $data = [];
    
    function validate($get,&$data,&$error){
        //$data = $get;
        if(count($get)<2){
            //$error[] = "Something's missing";
            echo "in to 1";
            return false;
        }
        else{
            if(!isset($get['a'])){
                $error[] = "a cannot be empty";
                //return false;
            }
            else if(filter_var($get['a'],FILTER_VALIDATE_FLOAT) === false){
                $error[] = "a must be float";
                //return false;
            }
            else{
                $a = (float)$get['a'];
                if($a === 0.0){
                    $error[] = "a must be non-zero";
                    //return false;
                }
                else{
                    $data['a'] = $a;
                }
            }

            if(!isset($get['b'])){
                $error[] = "b cannot be empty";
                //return false;
            }
            else if(filter_var($get['b'],FILTER_VALIDATE_FLOAT) === false){
                $error[] = "b must be float";
                //return false;
            }
            else{
                $data['b'] = (float)$get['b'];
            }
            return !count($error);
        }
    }

    $con = validate($_GET,$data,$error);
    if($con){
        $b = $data['b'];
        $a = $data['a'];
        $x = - $b / $a;
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP2</title>
</head>
<body>
    <?php if(count($error)>0) print_r($error) ?>

    <h1>ax + b = 0</h1>

    <form action="" method="GET">
        A : <input type="text" name="a" id="" value= <?= $_GET['a'] ?? ""?>>
        B : <input type="text" name="b" id="" value= <?= $_GET['b'] ?? ""?>>
        <button type="submit">CLICK</button>
    </form>

    <?php if(isset($x)) : ?>
        <p> x = <?= $x ?> </p>
    <?php endif ?>
</body>
</html>