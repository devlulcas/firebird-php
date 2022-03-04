<?php

namespace FirebdPHP\Firebird\Utils;

class StringUtils
{
    public static function removeInlineSpaces(string $string)
    {
        return rtrim(ltrim($string));
    }
}
