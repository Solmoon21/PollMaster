<?php
    include "storage.php";
    $db = new Storage(new JsonIO("users.json"));
    $fail = false;
    if(isset($_GET['username'],$_GET['password'])){
        $auth_user = $db->findOne(['username' => $_GET['username']]);
        if($auth_user && password_verify($_GET['password'],$auth_user['password'])){
            session_start();
            $_SESSION['user'] = $auth_user['id'];
            header('location: index.php');
            exit();
        }
        else{
            $fail = true;
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="get" action="">
        <label for="Username">Username</label>
        <input type="text" name="username" id="Username">
        <br><br>
        <label for="Username">Password</label>
        <input type="text" name="password" id="Password">
        <br><br>
        <button type="submit">Enter</button>
    </form>
    <?php if($fail): ?>
        <span class="error">Invalid username or password</span>
    <?php endif; ?>
</body>
</html>