<?php

namespace noone;

class Db
{

    protected App $app;

    protected array $instance = [];

    public function __construct(App $app)
    {

    }

    public function getConnection(string $id)
    {
        $policys = $this->app->config['database.policys'];
        if (!isset($policys[$id]))
        {
            throw new \Exception("policys {$id} not exists");
        }
        $config = $policys[$id];
        $class = "\\noone\\db\\" . $config['driver'];
        if (class_exists($class))
        {
            $driver = new $class($config);
        } else
        {
            throw new \Exception("class {$class} not exists");
        }

    }

    public function connection(string $id)
    {

    }
}
