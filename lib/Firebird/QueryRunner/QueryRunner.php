<?php

namespace FirebdPHP\Firebird\QueryRunner;

use PDOStatement;

class QueryRunner
{
    public static function run(string $query)
    {
        print_r($query);
    }
}
