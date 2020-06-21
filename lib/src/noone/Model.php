<?php

namespace noone;

use PDO;
use PDOException;

abstract class Model
{

    /**
     * 读库配置ID
     */
    protected string $readId = '';

    /**
     * 写库配置ID
     */
    protected string $writeId = '';

    protected static $db;

    public function __construct(string $writeId = '', string $readId = '')
    {
        $this->writeId = $writeId;
        $this->readId = $readId;
        print_r(self::$db);
    }

    public static function setDb($db)
    {
        self::$db = $db;
    }

    public function getDb()
    {
        $database = $this->config['database.policys'];
        $database = $database[$this->writeId];
        $dsn = "{$database['driver']}:dbname={$database['dbname']};host={$database['host']}";
        try {
            new PDO($dsn, $database['username'], $database['password']);
        } catch (PDOException $e) {
        }
    }
}
