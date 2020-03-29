<?php

namespace noone;


use ReflectionClass;

class App extends Container
{


    public static $bind = [
        'request' => Request::class
    ];

    public function __construct()
    {
    }

    public function run()
    {
        $this->route();
    }

    public function route()
    {
       
    }

    

    public static function getInstance($className)
    {
        $params = self::getParams($className);
        return (new ReflectionClass($className))->newInstanceArgs($params);
    }
}
