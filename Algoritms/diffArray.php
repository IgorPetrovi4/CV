<?php
declare(strict_types=1);
/* array_diff — Вычислить расхождение массивов, возвращает массив  $result из элементов которые осутствуют в каждом из масивов
 *
 * */
function diffArray(array $array1, array $array2)
{
    $result = [];
    foreach ($array1 as $value1) {
        if (inArray($value1, $array2) == false) {
            $result[] = $value1;
        }
    }
    foreach ($array2 as $value2) {
        if (inArray($value2, $array1) == false) {
            $result[] = $value2;
        }
    }

    return $result;

}

var_dump(diffArray([1, 2, 1, 3], [3, 4, 2, 5]));
