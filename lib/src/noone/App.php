<?php

namespace noone;


use ReflectionClass;

class App extends Container
{


    public $bind = [
        'request' => Request::class
    ];

    public function __construct()
    {
    }

    public function run()
    {
        $request = $this->request;
        print_r($request);
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
