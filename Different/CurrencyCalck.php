<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Конвертер валют</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
<?php
$api_url = 'http://api.currencylayer.com/';
$api_token = '33654f434440fafb83fb636edf511152';
$endpoints = 'live';

$quote_check = $_POST['quote'];
$ch = curl_init($api_url . $endpoints . '?access_key=' . $api_token . '&currencies=UAH,USD,EUR,' . implode(",", $quote_check));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json_data = json_decode(curl_exec($ch), true);
$quotes = $json_data['quotes'];
$date_api = $json_data['timestamp'];
curl_close($ch);
?>

<div class="form">
    <form method="post" action="CurrencyCalck.php">

        <label>Отдаем: <input type="text" name="val11"></label>
        <select name="val1">
            <?php
            foreach ($quotes as $quote => $value) {
                echo '<option value=' . substr($quote, -3) . '>' . substr($quote, -3) . '</option>';

            }
            ?>
        </select>
        <label>Конвертировать в :</label>

        <select name="val2">
            <?php

            foreach ($quotes as $quote => $value) {
                echo '<option value=' . substr($quote, -3) . '>' . substr($quote, -3) . '</option>';

            }
            ?>
        </select>

        <input type="submit" value="Просчитать"/>

        <label>

            <?php
            $currency_1 = $_POST['currency_1'];
            $currency_2 = $_POST['currency_2'];
            $amount = $_POST['amount'];
            $endpoints2 = 'convert';
            $ch2 = curl_init($api_url . $endpoints2 . '?access_key=' . $api_token . '&from=' . $currency_1 . '&to=' .  $currency_2 . '&amount=' . $amount);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            $json_data2 = json_decode(curl_exec($ch2), true);
            $convert = $json_data2['result'];
            curl_close($ch2);

            echo '<h1>' . $convert . '</h1>' . $currency_2;
            ?>

        </label>
    </form>

</div>

<br>


<?php
require_once 'config.php';
$date = date('Y-m-d H:i:s', $date_api);
$link = mysqli_connect($host, $user, $password, $database);
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_query($link, "CREATE TABLE IF NOT EXISTS date_convert(id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, data VARCHAR(30) NOT NULL, date_time DATETIME NOT NULL)");

if (!empty($convert)) {
    mysqli_query($link, "INSERT INTO date_convert(data, date_time) VALUES ('$convert $currency_2 из $amount  $currency_1', '$date')");

}

$count = mysqli_query($link, "SELECT id FROM date_convert");
$num_rows = mysqli_num_rows($count);


$limit = (int)$_POST['limit'];

$query_select = mysqli_query($link, "SELECT id, data, date_time FROM date_convert ORDER BY id DESC LIMIT $limit");

mysqli_close($link);

?>
<div class="checkForm">
    <h2>Настройки:</h2>

    <form name="quote" action="CurrencyCalck.php" method="post">
        <?php
        $ch_quotes = curl_init($api_url . $endpoints . '?access_key=' . $api_token);
        curl_setopt($ch_quotes, CURLOPT_RETURNTRANSFER, true);
        $json_data = json_decode(curl_exec($ch_quotes), true);
        $quotes_all = $json_data['quotes'];
        foreach ($quotes_all as $quote_all => $value) {
            echo '<input type="checkbox"  name="quote[]" value=' . substr($quote_all, -3) . '  /> ' . substr($quote_all, -3);


        }
        ?>

        <div class="checkButton">
            <input type="submit" value="Выбрать"/>
            <input type="reset" value="Сбросить"/>
        </div>

    </form>

</div>

<div class="history">
    <h2>Последние запросы:</h2>

    <form name="limit" action="CurrencyCalck.php" method="post">
        <input type="submit" name="limit" value="5">
        <input type="submit" name="limit" value="<?php echo $num_rows ?>">
    </form>



    <table class="history">

        <?php
        $rezult = mysqli_fetch_all($query_select);
        foreach ($rezult as $value => $item) {
            echo '<tr>
            <td>  Получено:     </td>
            <td>' . $item[1] . '</td>
            <td>  Дата запроса: </td>
            <td>' . $item[2] . '</td>
            </tr>';
        }
        ?>

    </table>
</div>


</body>
</html>