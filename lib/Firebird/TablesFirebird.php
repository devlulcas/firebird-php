<?php

namespace FirebdPHP\Firebird;


use FirebdPHP\Result;
use PDO;

class TablesFirebird extends FirebirdPHP
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Show all Tables in the DATABASE 
     */
    public function getTablesInDatabase(): void
    {
        $query = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS';
        $result = $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
        Result::showInConsole($result, "ALL TABLES IN DATABASE " . self::$databaseName . ":");
    }


    /**
     * Create a new table in the Database
     */
    public function createTable($table, $fields = []): bool
    {
        $fields = $this->getFields($fields);
        $createTableQuery = self::getCreateTableSql($table, $fields);
        $result = $this->execute($createTableQuery);
        return true;
    }


    /**
     * Turn array values into a single string.
     */
    private static function manipulateFieldsToCreateTable(string $value): string
    {
        $fields = "";
        $query = explode(":", $value);
        $tableName = strtoupper($query[0]);
        if (array_search("varchar", $query) || array_search("CHAR", $query)) {
            $isNotNull = $query[3] == "notNull" ? " NOT NULL" : "";
            $fields .= " " . $tableName . " " . "VARCHAR" . "(" . $query[2] . ")" . $isNotNull;
        } else {
            $isNotNull = $query[2] == "notNull" ? " NOT NULL" : "";
            $fields .= " " . $tableName . " " . strtoupper($query[1]) . $isNotNull;
        }
        return $fields;
    }



    /**
     * Manipulate array values ​​and turn them into a single string.
     */
    private function getFields(array $fields): string
    {
        $arrayFields = array_values($fields);
        $fields = "";
        $fieldsToCreate = array_map(function ($value) {
            return self::manipulateFieldsToCreateTable($value);
        }, $arrayFields);
        return implode(',', $fieldsToCreate);
    }
}
