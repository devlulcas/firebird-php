<?php

namespace FirebdPHP;


class Result
{

    public static function showInConsole($results, $title)
    {
        $tables = "";
        echo " \n";
        echo " | ${title}                                | \n";
        echo " \n";
        foreach ($results as $key => $value) {
            $table   = rtrim($value['RDB$RELATION_NAME']);
            $tables .= " | ${table} \n";
        }
        echo "${tables}  \n\n";
    }
}
