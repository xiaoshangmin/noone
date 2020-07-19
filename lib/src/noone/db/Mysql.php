<?php
 
namespace noone\db;

use noone\Connection;

class Mysql extends Connection
{

    public function parseDsn(array $config):string 
    {
        $dsn = "mysql:dbname={$config['dbname']};host={$config['host']}";
        if (!empty($config['charset'])) {
            $dsn .= ';charset=' . $config['charset'];
        }
        return $dsn;
    }
}