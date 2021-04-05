<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <title>Психологические тесты</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php
ob_start();
require_once ('config.php');

?>

<header>
    <div id="LogoImg">
        <a href="/" title="Перейти на главную"> Ψ</a>
    </div>
    <div id="Logo">
        <a href="/" title="Перейти на главную"> <span>Т</span>естування<span> В</span>ійськовослужбовця </a>
    </div>

</header>

<div id="TestMenu">


    <div id="Instruct">

        <?php

            if (!empty($_POST['t1'] && !empty($_POST['text']))){
                $consent = $_POST['t1'];
                $textField = $_POST['text'];

                $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest3(id,rezulttest3,rezulttest4) 
                                                  VALUES ('$fff',' $consent',' $textField')");

            }

        $result = mysqli_query($link, "SELECT * FROM register WHERE id=$fff");
        $row = mysqli_fetch_array($result);
        for ($i = 1; $i <= 6; $i++)
        {echo $reg="$row[$i]<br /> ";
        }
        $result = mysqli_query($link, "SELECT * FROM dbtest1 WHERE id=$fff");
        $row = mysqli_fetch_array($result);
        for ($i = 1; $i <= 1; $i++)
        {echo $db1="$row[$i]<br /> ";
        }
        $result = mysqli_query($link, "SELECT * FROM dbtest2 WHERE id=$fff");
        $row = mysqli_fetch_array($result);
        for ($i = 1; $i <= 1; $i++)
        {echo $db2="$row[$i]<br /> ";
        }
        $result = mysqli_query($link, "SELECT * FROM dbtest3 WHERE id=$fff");
        $row = mysqli_fetch_array($result);
        for ($i = 1; $i <= 2; $i++)
        {echo $db3="$row[$i]<br /> ";
        }
        ?>

    </div><!--I Инструкция-->

    <div id="form">
        <form action="emailrezult.php" method="post">
            <input type="submit" value="Відправити психологу">
    </div><!--Отправить-->

</div>

<footer>
    <?php
    echo $date->format("Y-m-d H:i:s") . PHP_EOL;
    ?>

</footer>



</body>
</html>
