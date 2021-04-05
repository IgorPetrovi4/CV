<?php

$tz = new \DateTimeZone("Europe/Kiev");
$date = new \DateTimeImmutable("now", $tz);


$link = mysqli_connect('localhost', 'root', '', 'psytestbase');
if(mysqli_connect_errno()){

    echo 'ошибка подключения к базе данных('.mysqli_connect_errno().'): '.mysqli_connect_errno();
    exit();
}
if (!empty($_COOKIE)){
    $fff=$_COOKIE["Cookie"];
}

