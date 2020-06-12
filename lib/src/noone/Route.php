<?php

namespace noone;

use Exception;

class Route
{

    public static array $methods = [];

    public static array $routes = [];

    /**
     * Closure|string
     */
    public static array $callbacks = [];

    public static $error;

    protected App $app;

    protected Request $request;

    public function __construct(App $app)
    {
        $this->app = $app;
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
        $routePath = $this->app->getRoutePath();
        if (is_dir($routePath)) {
            $files = glob($routePath . '*.php');
            foreach ($files as $file) {
                include_once $file;
            }
        }
    }

    public function dispatch(Request $request):Response
    {
        $uri = $request->server('REQUEST_URI');
        $method = $request->server('REQUEST_METHOD');
        $route_index = array_keys(self::$routes, $uri);
        //循环查找显示路由
        foreach ($route_index as $index) {
            if ((self::$methods[$index] == $method) && isset(self::$callbacks[$index])) {
                if (is_string(self::$callbacks[$index])) {
                    $data = $this->exec(self::$callbacks[$index]);
                } else {
                    $data = $this->app->resolve(self::$callbacks[$index]);
                }
                return $this->app->response->toResponse($data);
            }
        }
        $data = $this->exec($uri);
        return $this->app->response->toResponse($data);
    }

    public function exec(string $uri)
    {
        $path = $this->parseUri($uri);
        $class = $this->app->parseController($path[0]);
        if (class_exists($class)) {
            $instance = $this->app->resolve($class);
            $action = $path[1];
            if (is_callable([$instance, $action])) {
                return $this->app->invokeMethod($instance, $action);
            } else {
                throw new Exception("The action '{$action}' of Class '{$class}' is not exists");
            }
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    protected function parseUri(string $uri)
    {
        //默认的控制器和动作
        $controller = $action = 'index';
        // $uri = parse_url($uri, PHP_URL_PATH);
        if (!empty($uri)) {
            $uri = trim($uri, '/');
            $path = explode('/', $uri);

            if (!empty($path)) {
                if (count($path) >= 3) {
                    $module = array_shift($path);
                    $controller = array_shift($path);
                    $controller = "{$module}/{$controller}";
                    $action = array_shift($path);
                } else {
                    $controller = array_shift($path);
                    $action = array_shift($path) ?: 'index';
                }
            }
        }
        return [$controller, $action];
    }
}
