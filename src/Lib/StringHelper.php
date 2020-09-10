<?php

namespace App\Lib;

class StringHelper
{
    static public function getSubString($content, $afterStart, $beforeEnd)
    {
        $subString = strstr(trim(strstr($content, $afterStart), $afterStart), $beforeEnd, true);

        return $subString ? $subString : null;
    }
}
