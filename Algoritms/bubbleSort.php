<?php
declare(strict_types=1);
/* Сортировка пузырьком
 * */
function bubbleSort(array $array_value)
{
    for ($j = 0; $j < count($array_value) - 1; $j++) {
        for ($i = 0; $i < count($array_value) - 1; $i++) {
            if ($array_value[$i] > $array_value[$i + 1]) {
                $rotate = $array_value[$i];
                $array_value[$i] = $array_value[$i + 1];
                $array_value[$i + 1] = $rotate;
            }
        }
    }
    return $array_value;
}

var_dump(bubbleSort([3, 4, 1, 7, 2]));
