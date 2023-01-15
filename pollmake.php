<?php
    $data = [];
    $error = [];
    $succ = true;

    function validate($get,&$data,&$error){
        $data = $get;
        if(count($data)>0){
            if(!isset($get['isMultiple']) || empty($get['isMultiple'])){
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
        }

        if(isset($get['isMultiple'])){
            if(empty($get['isMultiple'])){
                $error['isMultiple'] = "Type must be given";
            }
        }

        return count($error) == 0;
    }
    $succ = validate($_GET,$data,$error);
    $text = count($data)==0 ? "" : ($succ ? "Your poll has been created" : "Your poll has missing attributes");

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
    <form id="f" action="" method="get">
        <label for="Title">Title:</label> <input type="text" name="title" id="Title" value=<?= $_GET['title']?? ""?>>
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
        <textarea type="text" name="options" id="Options" value=<?= $_GET['options']?? ""?> rows="5" cols="80" ></textarea>
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
        <input type="radio" name="isMultiple" id="Yes" value=1 <?php if(isset($_GET['isMultiple'])&&$_GET['isMultiple']=="1") echo "checked = checked"; ?>  >
        <label for="No">No</label> 
            <input type="radio" name="isMultiple" id="No" value=0 <?php if(isset($_GET['isMultiple'])&&$_GET['isMultiple']=="0") echo "checked = checked"; ?>>
        <br><br>
        <label for="End">Deadline:</label>
        <input type="date" name="end" id="End">
        <?php 
            if (isset($error['end'])){
                echo "<span class='error'>";
                echo $error['end'];
                echo "</span>";
            }
        ?>
        <br><br>
        <input name="start" type="hidden">
        <button type="submit">Create</button>
    </form>

    <?php if(!empty($text)): ?>
        <h1 style="text-align:center" class= <?= $succ?"success":"error" ?>> <?=$text?>  </h1>
    <?php endif; ?>

    <script>
        var form = document.getElementById('f')
        var start = document.querySelector("input[name='start']");
        form.addEventListener('submit',function(event){
            var now = new Date().toLocaleDateString() // => 1/15/2023
            // new Date.toLocaleString() => 1/15/2023, 5:18:40 AM
            start.value = now // string
            //console.log(typeof start.value) 
        })
    </script>

</body>
</html>