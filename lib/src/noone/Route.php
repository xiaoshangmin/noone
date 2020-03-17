<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-17 17:07:48
 * @LastEditTime: 2020-03-17 18:15:47
 * @Description: 
 */

namespace noone;

class Route
{

    public static $methods = [];

    public static $routes = [];

    public static $callbacks = [];

    public function __callStatic(string $method, array $arguments)
    {
        $callback = $arguments[1];
        $route = $arguments[0];

        self::$methods[] = $method;
        self::$callbacks[] = $callback;
        self::$routes[] = $route;
    }

    public function dispatch()
    {
        
    }
}

Route::get('/', 'module/controller/action');
