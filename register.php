<?php
    include "storage.php";
    $db = new Storage(new JsonIO("users.json"));
    $data = [];
    $error = [];
    
    function validate($get,&$data,&$error){
        if(count($get) == 0){
            return false;
        }

        global $db;
        $data = $get;
        if(count($data) > 0){
            if(!isset($get['username']) || empty($get['username'])){
                $error['username'] = "Username can't be null";
            }
            else if($db->findOne(['username' => $get['username']])){
                $error['username'] = "Username already exists";
            }
            
            if(!isset($get['email']) || empty($get['email'])){
                $error['email'] = "Email can't be null";
            }
            else if(!preg_match('/[a-zA-Z0-9]+@gmail\.hu/',$get['email'])){
                $error['email'] = "Invalid email format";
            }

            if(!isset($get['password']) || empty($get['password'])){
                $error['password'] = "Password can't be null";
            }
            else if(!isset($get['repassword']) || empty($get['repassword'])){
                $error['repassword'] = "Second password can't be null";
            }
            else if($get['password'] != $get['repassword']){
                $error['password'] = $error['repassword'] = "Passwords must match";
            }
            
        }
        return count($error) == 0;
    }

    if(validate($_GET,$data,$error)){
        unset($data['repassword']);
        $data['isAdmin'] = FALSE;
        $data['password'] = password_hash($data['password'],PASSWORD_DEFAULT); 
        $db->add($data);
        header('location: login.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="get" action="" novalidate>
        <label for="Username">Username</label>
        <input type="text" name="username" id="Username" value=<?= $_GET['username']?? ""?>>
        <?php 
            if (isset($error['username'])){
                echo "<span class='error'>";
                echo $error['username'];
                echo "</span>";
            }
        ?>
        <br><br>
        <label for="Email">Email</label>
        <input type="text" name="email" id="Email" value=<?= $_GET['email']?? ""?>>
        <?php 
            if (isset($error['email'])){
                echo "<span class='error'>";
                echo $error['email'];
                echo "</span>";
            }
        ?>
        <br><br>
        <label for="Password">New Password</label>
        <input type="text" name="password" id="Password" value=<?= $_GET['password']?? ""?>>
        <?php 
            if (isset($error['password'])){
                echo "<span class='error'>";
                echo $error['password'];
                echo "</span>";
            }
        ?>
        <br><br>
        <label for="RePassword">Re-enter Password</label>
        <input type="text" name="repassword" id="RePassword" value=<?= $_GET['repassword']?? ""?>>
        <?php 
            if (isset($error['repassword'])){
                echo "<span class='error'>";
                echo $error['repassword'];
                echo "</span>";
            }
        ?>
        <br><br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>