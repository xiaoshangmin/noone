<?php

namespace noone;

class Db
{

    protected App $app;

    /**
     * 数据库连接实例
     * @var array
     */
    protected array $instance = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function fetch($sql)
    {
       return 'fetch';
    }

    public function fetchAll($sql)
    {
        return 'fetchAll';
    }

    public function exec($sql)
    {
        return 'exec';
    }


    public function getConnection(string $id)
    {
        if (isset($this->instance[$id])) {
            return $this->instance[$id];
        }
        $policys = $this->app->config['database.policys'];
        if (!isset($policys[$id])) {
            throw new \Exception("policys '{$id}' not exists");
        }
        $config = $policys[$id];
        $class = "\\noone\\db\\" . $config['driver'];
        if (class_exists($class)) {
            $driver = new $class($config);
            $this->instance[$id] = $driver;
        } else {
            throw new \Exception("class {$class} not exists");
        }
    }
}
