<?php

namespace FirebdPHP\Firebird;

use FirebdPHP\Env\EnvFiles;
use FirebdPHP\Result;
use PDOException;
use PDOStatement;
use PDO;

class FirebirdPHP
{
    private static $driver   =  "firebird";
    private static $host;
    private static $password;
    private static $user;
    protected static $nameDb;
    protected $connection;


    public function __construct()
    {
        $this->setConnection();
    }


    /**
     * sets the path of the .fdb file
     * @param string
     */
    private function setFdbPath(string $path): void
    {
        $this->fdbPath = $path;
    }


    /**
     * Sets the credentials to connect to database
     * @param string
     */
    public static function config(string $envPath): void
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
     * Execute a Sql Query
     * @param string
     */
    protected function execute(string $sqlQuery): ?PDOStatement
    {
        try {
            $statement = $this->getConnection()->prepare($sqlQuery);
            $statement->execute();
            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getCode());
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
    protected static function getCreateTableSql(string $table, string $fields): string
    {
        $table = strtoupper($table);
        return "CREATE TABLE  ${table}(${fields})";
    }
}
