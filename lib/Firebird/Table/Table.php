<?php

namespace FirebdPHP\Firebird\Table;

use FirebdPHP\Firebird\Column\GenerateColumns;

class Table
{
    /**
     * Returns a query string to create a table with the given name and columns
     */
    public static function create(string $name, array $columns)
    {
        $columns = GenerateColumns::createMultiple($columns);
        return "CREATE TABLE  $name ($columns)";
    }
}
