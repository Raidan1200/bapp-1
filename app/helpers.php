<?php

if (! function_exists('money')) {
    function money(int $value)
    {
        return number_format(round($value / 100, 2), 2, ',', '.');
    }
}
