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
require_once 'vendor/autoload.php';
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
    <fieldset>
        <legend>Реєстрація</legend>
        <form action="test1.php" method="post" ><br>
            <label>Прізвище<input type="text" name="lastName" required></label><br>
            <label>Імя<input type="text" name="name" required></label><br>
            <label>По Батькові<input type="text" name="patronymic" required></label><br>
            <label>Військова частина<input type="text" name="militaryUnit" required></label><br>
            <label>Ваш E-mail<input type="email" name="email" required></label><br>
            <label>E-mail Вашого психолога<input type="email" name="psyEmail" required></label><br>
            <input type="submit" name="">
        </form>
    </fieldset>

</div>

    <footer>
        <?php
        echo $date->format("Y-m-d H:i:s") . PHP_EOL;
        ?>

    </footer>



</body>
</html>
