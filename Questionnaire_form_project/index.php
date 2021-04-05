<!DOCTYPE html>
<html lang="en">
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

<div id="News">

</div>

<div id="TestMenu">

    <a href="registr1.php" title="Перейти на главную"><div id="Test1">
       Тест 1
        </div></a>
    <a href="/" title="Перейти на главную"><div id="Test2">
            Тест 2
        </div></a>
    <a href="/" title="Перейти на главную"><div id="Test3">
            Тест 3
        </div></a>
    <a href="/" title="Перейти на главную"><div id="Test4">
            Тест 4
        </div></a>
    <a href="/" title="Перейти на главную"><div id="Test5">
            Тест 5
        </div></a>
    <a href="/" title="Перейти на главную"><div id="Test6">
            Тест 6
        </div></a>

</div>

<footer>
<?php
echo $date->format("Y-m-d H:i:s") . PHP_EOL;
?>

</footer>

</body>
</html>


