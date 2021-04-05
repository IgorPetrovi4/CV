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


            <p>ІІІ.	Чи згодні Ви із зазначеним висловом?:   </p><br>
            <p>“Я маю необхідні знання і практичні навички, вивчив функціональні обов’язки,
                пройшов відповідний інструктаж, його вимоги мені зрозумілі, проблемних питань
                щодо організації несення служби не маю.
                Мій стан здоров’я, настрій, самопочуття і активність,
                морально-психологічний стан дозволяють мені виконувати службові обов’язки із зброєю.
                Проблемних питань, негативних чинників впливу на мій морально-психологічний стан не маю.
                Готовий нести службу зі зброєю”.<br>
            </p>

           <form action="rezult.php" method="post">


                   <input type="radio" name="t1" value="ТАК ГОТОВИЙ" required > Так <br>
                   <input type="radio" name="t1" value="НІ НЕ ГОТОВИЙ" required >  Ні <br>


                   <p><b>Введите ваш отзыв:</b></p>
                   <p><textarea rows="10" cols="45" name="text"></textarea></p>
                   <p><input type="submit" value="Отправить"></p>

               </form>
</div>


<?php

    $range= array_sum($_POST);
    $CAH = $range*3/10;

    if ($CAH <=20){
        $rezCah= "Індекс САН НИЗЬКИЙ";

        $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest2(id,rezulttest2) 
                                                  VALUES ('$fff','$rezCah')");
    }
    elseif ($CAH >= 21 && $CAH <=60){
        $rezCah= "Індекс САН СЕРЕДНІЙ";

       $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest2 (id,rezulttest2) 
                                                  VALUES ('$fff','$rezCah')");
    }
    elseif ($CAH >= 61) {
        $rezCah= "Індекс САН  ВИСОКИЙ";

        $sql = mysqli_query($link,"
                                                  INSERT INTO dbtest2 (id,rezulttest2) 
                                                  VALUES ('$fff','$rezCah')");
    }

?> <!--Скрипт формы test11.php-->
<footer>
    <?php
    echo $date->format("Y-m-d H:i:s") . PHP_EOL;
    ?>
</footer>
</body>
</html>

