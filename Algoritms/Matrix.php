<?php
declare(strict_types=1);
/* вычисление элементов в матрице типа :
[0, 0, 0, 1];
[0, 0, 1, 1];
[0, 1, 1, 1];
[1, 1, 1, 1];
 * возвращает количетсво элементов и их сумму
 * */
function matrix(array $row1, array $row2, array $row3, array $row4)
{
    $matrix = [$row1, $row2, $row3, $row4];
    $counter = 0;
    $sum = 0;
    foreach ($matrix as $row) {
        foreach ($row as $value) {
            $sum = $value + $sum;
            if ($value > 0) {
                $counter = $counter + 1;
            }

        }

    }
    return "Количество элементов:" . $counter . "<br>" . "Сумма элементов:" . $sum;
}

echo matrix([0, 0, 0, 1], [0, 0, 1, 1], [0, 1, 1, 1], [1, 1, 1, 1]);
