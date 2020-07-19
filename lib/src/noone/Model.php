<?php

namespace noone;

use PDO;
use PDOException;

abstract class Model
{

    protected App $app;
    /**
     * 读库配置ID
     */
    protected string $readId = 'default';

    /**
     * 写库配置ID
     */
    protected string $writeId = 'default';


    public function __construct(App $app)
    {
        $this->app = $app;
    }


    public function __get($abstract)
    {
        return $this->app->{$abstract};
    }

    public function __call(string $method, array $args = [])
    {
        $method = strtolower($method);
        return call_user_func_array([$this->app->db, $method], $args);
    }
}
