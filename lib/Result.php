<?php

namespace FirebdPHP;


class Result {

    public static function getResult($results)
    {

        $tables = "\n\nAll Tables: \n \r \n \r";

        foreach ($results as $key => $value) {
            $tables .= $value['RDB$RELATION_NAME'] . "\n \r";
        }

        echo "" . $tables . "\n \r";
    }

}