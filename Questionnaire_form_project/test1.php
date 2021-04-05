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
        І. Інструкція. <br> Прочитайте уважно кожне з наведених нижче речень і виберіть відповідну цифру нижче
        залежно від того, ЯК ВИ ПОЧУВАЄТЕСЯ ЦІЄЇ МИТІ. <br>Над запитаннями довго не замислюйтеся, тому що правильних
        чи неправильних відповідей немає.
    </div><!--I Инструкция-->
    <div id="form">
        <form action="test11.php" method="post">
            <p>1. Я спокійний </p>
            <label><input type="radio" name="a1" value="1" required> 1</label>
            <label><input type="radio" name="a1" value="2" required> 2</label>
            <label><input type="radio" name="a1" value="3" required> 3</label>
            <label><input type="radio" name="a1" value="4" required> 4</label>

            <p>2. Мені ніщо не загрожує </p>
            <label><input type="radio" name="a2" value="1" required> 1</label>
            <label><input type="radio" name="a2" value="2" required> 2</label>
            <label><input type="radio" name="a2" value="3" required> 3</label>
            <label><input type="radio" name="a2" value="4" required> 4</label>

            <p>3. Я перебуваю в напруженні </p>
            <label><input type="radio" name="a3" value="1" required> 1</label>
            <label><input type="radio" name="a3" value="2" required> 2</label>
            <label><input type="radio" name="a3" value="3" required> 3</label>
            <label><input type="radio" name="a3" value="4" required> 4</label>

            <p>4. Мене проймає жаль до себе </p>
            <label><input type="radio" name="a4" value="1" required> 1</label>
            <label><input type="radio" name="a4" value="2" required> 2</label>
            <label><input type="radio" name="a4" value="3" required> 3</label>
            <label><input type="radio" name="a4" value="4" required> 4</label>

            <p>5. Я почуваюся вільно </p>
            <label><input type="radio" name="a5" value="1" required> 1</label>
            <label><input type="radio" name="a5" value="2" required> 2</label>
            <label><input type="radio" name="a5" value="3" required> 3</label>
            <label><input type="radio" name="a5" value="4" required> 4</label>

            <p>6. Я переживаю стан одинокості </p>
            <label><input type="radio" name="a6" value="1" required> 1</label>
            <label><input type="radio" name="a6" value="2" required> 2</label>
            <label><input type="radio" name="a6" value="3" required> 3</label>
            <label><input type="radio" name="a6" value="4" required> 4</label>

            <p>7. Мене хвилюють можливі невдачі </p>
            <label><input type="radio" name="a7" value="1" required> 1</label>
            <label><input type="radio" name="a7" value="2" required> 2</label>
            <label><input type="radio" name="a7" value="3" required> 3</label>
            <label><input type="radio" name="a7" value="4" required> 4</label>

            <p>8. Я почуваю себе відпочившим </p>
            <label><input type="radio" name="a8" value="1" required> 1</label>
            <label><input type="radio" name="a8" value="2" required> 2</label>
            <label><input type="radio" name="a8" value="3" required> 3</label>
            <label><input type="radio" name="a8" value="4" required> 4</label>

            <p>9. Я схвильований  </p>
            <label><input type="radio" name="a9" value="1" required> 1</label>
            <label><input type="radio" name="a9" value="2" required> 2</label>
            <label><input type="radio" name="a9" value="3" required> 3</label>
            <label><input type="radio" name="a9" value="4" required> 4</label>

            <p>10. Я відчуваю відчуття внутрішнього задоволення  </p>
            <label><input type="radio" name="a10" value="1" required> 1</label>
            <label><input type="radio" name="a10" value="2" required> 2</label>
            <label><input type="radio" name="a10" value="3" required> 3</label>
            <label><input type="radio" name="a10" value="4" required> 4</label>

            <p>11. Я впевнений у собі  </p>
            <label><input type="radio" name="a11" value="1" required> 1</label>
            <label><input type="radio" name="a11" value="2" required> 2</label>
            <label><input type="radio" name="a11" value="3" required> 3</label>
            <label><input type="radio" name="a11" value="4" required> 4</label>

            <p>12. Я нервуюся  </p>
            <label><input type="radio" name="a12" value="1" required> 1</label>
            <label><input type="radio" name="a12" value="2" required> 2</label>
            <label><input type="radio" name="a12" value="3" required> 3</label>
            <label><input type="radio" name="a12" value="4" required> 4</label>

            <p>13. Я не знаходжу собі місця  </p>
            <label><input type="radio" name="a13" value="1" required> 1</label>
            <label><input type="radio" name="a13" value="2" required> 2</label>
            <label><input type="radio" name="a13" value="3" required> 3</label>
            <label><input type="radio" name="a13" value="4" required> 4</label>

            <p>14. Я збуджений (роздратований)   </p>
            <label><input type="radio" name="a14" value="1" required> 1</label>
            <label><input type="radio" name="a14" value="2" required> 2</label>
            <label><input type="radio" name="a14" value="3" required> 3</label>
            <label><input type="radio" name="a14" value="4" required> 4</label>

            <p>15. Я не відчуваю скутості, напруження   </p>
            <label><input type="radio" name="a15" value="1" required> 1</label>
            <label><input type="radio" name="a15" value="2" required> 2</label>
            <label><input type="radio" name="a15" value="3" required> 3</label>
            <label><input type="radio" name="a15" value="4" required> 4</label>

            <p>16. Я задоволений    </p>
            <label><input type="radio" name="a16" value="1" required> 1</label>
            <label><input type="radio" name="a16" value="2" required> 2</label>
            <label><input type="radio" name="a16" value="3" required> 3</label>
            <label><input type="radio" name="a16" value="4" required> 4</label>

            <p>17. Я стурбований    </p>
            <label><input type="radio" name="a17" value="1" required> 1</label>
            <label><input type="radio" name="a17" value="2" required> 2</label>
            <label><input type="radio" name="a17" value="3" required> 3</label>
            <label><input type="radio" name="a17" value="4" required> 4</label>

            <p>18. Я надто збуджений і мені не по собі    </p>
            <label><input type="radio" name="a18" value="1" required> 1</label>
            <label><input type="radio" name="a18" value="2" required> 2</label>
            <label><input type="radio" name="a18" value="3" required> 3</label>
            <label><input type="radio" name="a18" value="4" required> 4</label>

            <p>19. Мені радісно    </p>
            <label><input type="radio" name="a19" value="1" required> 1</label>
            <label><input type="radio" name="a19" value="2" required> 2</label>
            <label><input type="radio" name="a19" value="3" required> 3</label>
            <label><input type="radio" name="a19" value="4" required> 4</label>

            <p>20. Мені приємно    </p>
            <label><input type="radio" name="a20" value="1" required> 1</label>
            <label><input type="radio" name="a20" value="2" required> 2</label>
            <label><input type="radio" name="a20" value="3" required> 3</label>
            <label><input type="radio" name="a20" value="4" required> 4</label><br>
            <input type="submit" value="Send Request">

        </form>

    </div>


<div id="form">
<?php
        if (!empty($_POST['lastName']) && !empty($_POST['name']) && !empty($_POST['patronymic'])&& !empty($_POST['militaryUnit'])&& !empty($_POST['email'])
            && !empty($_POST['psyEmail'])){
            $lastName=$_POST['lastName'];
            $name=$_POST['name'];
            $patronymic=$_POST['patronymic'];
            $military=$_POST['militaryUnit'];
            $email=$_POST['email'];
            $psyMail=$_POST['psyEmail'];
            $sql = mysqli_query($link,"    INSERT INTO register (lastName,name,patronymic,militaryUnit,email,psyEmail) 
                                                  VALUES ('$lastName', '$name','$patronymic','$military','$email','$psyMail')
                                                ");
            $fff = mysqli_insert_id($link);
            setcookie("Cookie",$fff);

        }
        ?>


</div>
<footer>
<?php
echo $date->format("Y-m-d H:i:s") . PHP_EOL;

?>

</footer>



</body>
</html>
