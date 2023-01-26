<?php

namespace App\Helpers;

class Helpers
{
    public function swap(&$a, &$b): void
    {
        $temp = $a;
        $a = $b;
        $b = $temp;
    }
}
