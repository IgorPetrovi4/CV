<?php
declare(strict_types=1);
/*
 * Бинарная сортировка 
 */

function binarySearch(int $needle, array $haystack): bool
{

    $center = intdiv(count($haystack), 2);
    $center_value = $haystack[$center];

    if (empty($needle) || $needle > end($haystack)|| $needle < $haystack[0]) {
        return false;
    }
    if ($needle == $center_value) {
        return true;
    }
    if ($needle < $center_value) {
        return binarySearch($needle, array_slice($haystack, 0, $center));

    }
    if ($needle > $center_value) {
        return binarySearch($needle, array_slice($haystack, $center));

    }


    return false;
}

echo binarySearch(14, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]);

echo binarySearch(-15, [-15, -14, -13, -12, -11, -10, -9, -8, -7, -6, -5, -4, -3, -2, -1]);