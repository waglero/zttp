<?php
namespace Zttp;

if (!function_exists('tap')) {
    function tap($value, $callback)
    {
        $callback($value);
        return $value;
    }
}
