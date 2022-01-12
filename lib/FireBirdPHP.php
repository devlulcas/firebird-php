<?php

namespace FirebdPHP;

use Exception;
use PDO;
use PDOException;
use PDOStatement;

class FireBirdPHP
{

    private static $driver   =  "firebird";

    private static $host     =  "127.0.0.1:/home/philipe/Bancos/";
    private static $password =  "masterkey";
    private static $user     =  "SYSDBA";
    private static $nameDb   =  "TESTE.fdb";


    private $connection;



    public function __construct()
    {
        $this->setConnection();
    }

    /**
     * sets the path of the .fdb file
     * 
     * @param string
     */
    private function setFdbPath(string $path): void
    {
        $this->fdbPath = $path;
    }


    /**
     * Sets the credentials to connect to database
     * 
     * @param string
     */
    public static function setCredentials(string $envPath): void
    {
        EnvFiles::loadEnv($envPath);
    }




    /** 
     * Sets the database connection.
     * @return void
     */
    private function setConnection(): void
    {
        try {

            $config = self::$driver . ':dbname=' . self::$host . self::$nameDb . ';charset=utf8;dialect=3';

            $this->connection = new PDO($config, self::$user, self::$password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            die('ERROR: ' . $e->getMessage());

        }
    }


    /**
     * Return the database connection.
     * @return PDO
     */
    private  function getConnection(): PDO
    {
        return $this->connection;
    }



    /**
     * Show all Tables 
     * @return void
     */
    public function getTablesInDatabase(): void
    {
        $query = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS';
        $result = $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
        Result::getResult($result);
    }


    /**
     * Show all Views 
     * 
     */
    public function getViewsInDatabase(): ?string
    {
        $query = 'SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE NOT RDB$VIEW_BLR IS NULL';
        $result = $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
        return $result ? Result::getResult($result) : "";
    }



    /**
     * Create a new table
     * @return bool
     */
    public function createTable($table, $fields = []): bool
    {
        $fields = $this->getFields($fields);
        $createTableQuery = self::getCreateTableSql($table, $fields);
        $result = $this->execute($createTableQuery);

        return true;
        
    }


    /**
     * manipulate array values ​​and turn them into a single string.
     * @param array
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


    /**
     * turn array values into a single string.
     * @param string 
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
     * Execute a Sql Query
     * @param string
     */
    private function execute(string $sqlQuery): ?PDOStatement
    {
        try {

            $statement = $this->getConnection()->prepare($sqlQuery);
            $statement->execute();
            return $statement;

        } catch (PDOException $e) {

            die('ERROR: ' . $e->getMessage());
     
        }
    }


    /**
     * Execute a Sql Query with params
     * @param string
     * @param array
     */
    private function executeQuery(string $query, array $params = []): ?PDOStatement
    {

        try {

            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement;

        } catch (PDOException $e) {

            switch ($e->getCode()) {
                case 23000:
                    throw new \Exception('Dados já existentes!');
                default:
                    die('ERROR: ' . $e->getMessage());
            }
        }
    }


    /**
     * return a Create table query
     * @param string 
     * @param string
     */
    private static function getCreateTableSql(string $table, string $fields): string
    {

        $table = strtoupper($table);
        return "CREATE TABLE  ${table}(${fields})";
    }

    
}
