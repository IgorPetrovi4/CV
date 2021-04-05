<?php
declare(strict_types=1);
/* in_array — Проверяет, присутствует ли в массиве $haystack  значение $needle
 *
 * */
function inArray(int $needle, array $haystack): bool
{
    foreach ($haystack as $value) {
        if ($needle == $value) {
            return true;
        }

    }
    return false;
}

var_dump(inArray(5, [5, 6, 7]));
