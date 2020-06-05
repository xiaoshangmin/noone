<?php

namespace noone;

class Route
{

    public static array $methods = [];

    public static array $routes = [];

    public static array $callbacks = [];

    public static $error;

    protected App $app;

    protected Request $request;

    public function __construct(App $app,Request $request)
    { 
        $this->app = $app;
        $this->request = $request;
    }

    public static function __callStatic(string $method, array $arguments)
    {

        $routePath = $arguments[0];
        $callback = $arguments[1];

        self::$methods[] = strtoupper($method);
        self::$callbacks[] = $callback;
        self::$routes[] = $routePath;
    }

    public function loadRoutes()
    {
        $routeFile = $this->app->getAppPath() . "route" . DIRECTORY_SEPARATOR . "route.php";
        if (file_exists($routeFile)) {
            include_once $routeFile;
        }
    }

    public function dispatch()
    {
        $uri = $this->parseUrl($this->request->server('REQUEST_URI'));
        $found_route = false;
        $method = $this->request->server('REQUEST_METHOD');
        //首先查找显示路由
        $route_index = array_keys(self::$routes, $uri);
        foreach ($route_index as $index) {
            if ((self::$methods[$index] == $method) && isset(self::$callbacks[$index])) {
                if (is_string(self::$callbacks[$index])){
                    print_r(self::$callbacks[$index]);
                    exit();
                }else
                {
                    $this->app->exec(self::$callbacks[$index]);
                }
                $found_route = true;
                break;
            }
        }

        //查找隐式路由
        if (!$found_route){
            $path = $this->parseUrl($this->request->server('REQUEST_URI'));
            $class = $this->app->parseController($path[0]);
            if (class_exists($class)){
                $instance = $this->app->exec($class);
                $action = $path[1];
                if (is_callable([$instance,$action])){
                    $res = $this->app->invokeMethod($instance,$action);
                    print_r($res);
                }else{
                    echo $action;
                }

            }
            $found_route = true;
        }

        if (!$found_route) {
            if (!self::$error) {
                self::$error = function () {
                    echo "404 Not Found!";
                };
                call_user_func(self::$error);
            } elseif (is_string(self::$error)) {
                self::get($this->request->server('REQUEST_URI'), self::$error);
            } elseif (self::$error instanceof \Closure) {
                call_user_func(self::$error);
            }
        }
    }

    protected function parseUrl(string $url)
    {
        $uri = parse_url($url, PHP_URL_PATH);
        $uri = trim($uri,'/');
        if (empty($uri)){
            return [];
        }
        $path = explode('/',$uri);
        //TODO 可配置默认值
        $controller = $action = 'index';
        if (!empty($path)){
            if(count($path) >= 3)
            {
                $module = array_shift($path);
                $controller = array_shift($path);
                $controller = "{$module}/{$controller}";
                $action = array_shift($path);
            }else{
                $controller = array_shift($path);
                $action = array_shift($path) ?: 'index';
            }
        }

        return [$controller,$action];
    }
}
