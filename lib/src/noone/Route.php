<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-17 17:07:48
 * @LastEditTime: 2020-04-02 19:40:28
 * @Description: 
 */

namespace noone;

class Route
{

    public static $methods = [];

    public static $routes = [];

    public static $callbacks = [];

    public static $error;

    protected $app;

    public function __construct(App $app)
    { 
        $this->app = $app;
    }

    public static function __callStatic(string $method, array $arguments)
    {

        $route = $arguments[0];
        $callback = $arguments[1];

        self::$methods[] = strtoupper($method);
        self::$callbacks[] = $callback;
        self::$routes[] = $route;
    }

    public function loadRoutes()
    {
        $routeFile = $this->app->rootPath . "route" . DIRECTORY_SEPARATOR . "route.php";
        if (file_exists($routeFile)) {
            include_once $routeFile;
        }
    }

    public static function dispatch()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ('/' != $uri) {
            $uri = rtrim($uri, '/');
        }
        $found_route = false;
        // $query_string = $_SERVER['QUERY_STRING'];
        $method = $_SERVER['REQUEST_METHOD'];
        $route_indexs = array_keys(self::$routes, $uri);
        foreach ($route_indexs as $index) {
            if ((self::$methods[$index] == $method) && isset(self::$callbacks[$index])) {
                Container::getInstance()->exec(self::$callbacks[$index]);
                return;
                // if (self::$callbacks[$index] instanceof \Closure) {
                //     $found_route = true;
                //     call_user_func(self::$callbacks[$index]);
                //     return;
                // }
            }
        }
        if (!$found_route) {
            if (!self::$error) {
                self::$error = function () {
                    echo "404 Not Found!";
                };
                call_user_func(self::$error);
            } elseif (is_string(self::$error)) {
                self::get($_SERVER['REQUEST_URI'], self::$error);
            } elseif (self::$error instanceof \Closure) {
                call_user_func(self::$error);
            }
        }
    }
}
