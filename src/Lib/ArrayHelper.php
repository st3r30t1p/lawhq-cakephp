<?php

namespace App\Lib;

class ArrayHelper
{
    static public function filterArray($array, $operator, $arrayIndex)
    {
        $newArr = array_filter($array, function ($k) use ($operator, $arrayIndex) {

            if ($operator == '<') {
                return $k < $arrayIndex;
            } elseif ($operator == '>') {
                return $k < $arrayIndex;
            } elseif ($operator == '>=') {
                return $k >= $arrayIndex;
            }

        }, ARRAY_FILTER_USE_KEY);

        return implode('', $newArr);
    }
}
