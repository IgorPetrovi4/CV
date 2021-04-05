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

    <div id="form">
        <p>ІІ.Поставте позначку на шкалі, яка відповідає інтенсивності вияву зазначених чинників:</p>

        <p>САМОПОЧУТТЯ</p>
        <form action="test111.php" method="post">
        <input type="range" name="r1" min="0" max="100" step="1"><br>

        <p>АКТИВНІСТЬ</p>
        <input type="range" name="r2" min="0" max="100" step="1"><br>

        <p>НАСТРІЙ</p>
        <input type="range" name="r3" min="0" max="100" step="1"><br>

        <input type="submit" value="Send Request"><br>
        </form>


    </div>

</div><!--Шкала настроения-->

<?php

if (!empty($_POST)) {
    if (!empty($_POST['a1']) && !empty($_POST['a2']) && !empty($_POST['a3']) && !empty($_POST['a4']) && !empty($_POST['a5'])
        && !empty($_POST['a6']) && !empty($_POST['a7']) && !empty($_POST['a8']) && !empty($_POST['a9']) && !empty($_POST['a10'])
        && !empty($_POST['a11']) && !empty($_POST['a12']) && !empty($_POST['a13']) && !empty($_POST['a14']) && !empty($_POST['a15'])
        && !empty($_POST['a16']) && !empty($_POST['a17']) && !empty($_POST['a18']) && !empty($_POST['a19']) && !empty($_POST['a20'])) {


        $result1 = array($_POST['a3'], $_POST['a4'], $_POST['a6'], $_POST['a7'], $_POST['a9'], $_POST['a12'], $_POST['a13'], $_POST['a14'], $_POST['a17'], $_POST['a18']);
        $result2 = array($_POST['a1'], $_POST['a2'], $_POST['a5'], $_POST['a8'], $_POST['a10'], $_POST['a11'], $_POST['a15'], $_POST['a16'], $_POST['a19'], $_POST['a20']);
        $e = 35;
        $RT = array_sum($result1) - array_sum($result2) + $e;
        $a = 30;
        $b = 31;
        $c = 45;
        $d = 46;

        if ($RT <= $a){
            $rez= "Реактивна тривожність НИЗЬКА";

            $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest1 (id,rezulttest1) 
                                                  VALUES ('$fff','$rez')");
        }
        elseif ($RT >= $b && $RT <= $c){
            $rez= "Реактивна тривожність ПОМІРНА";

            $fg=mysqli_insert_id($link );
            $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest1 (id,rezulttest1) 
                                                  VALUES ('$fff','$rez')");
        }
        elseif ($RT >= $d) {
            $rez= "Реактивна тривожність ВИСОКА";

            $fg=mysqli_insert_id($link );
            $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest1 (id,rezulttest1) 
                                                  VALUES ('$fff','$rez')");
        }

    }else {echo 'Заповніть всі поля';}

}


?><!-- Скрипт формы test1.php-->

<footer>
    <?php
    echo $date->format("Y-m-d H:i:s") . PHP_EOL;
    ?>

</footer>



</body>
</html>

