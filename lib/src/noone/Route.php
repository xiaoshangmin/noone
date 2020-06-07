<?php

namespace noone;

use Exception;

class Route
{

    public static array $methods = [];

    public static array $routes = [];

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

    public function dispatch(Request $request): Response
    {
        $uri = $request->server('REQUEST_URI');
        $method = $request->server('REQUEST_METHOD');
        //首先查找显示路由
        $route_index = array_keys(self::$routes, $uri);
        //循环查找对应请求动作的路由
        foreach ($route_index as $index) {
            if ((self::$methods[$index] == $method) && isset(self::$callbacks[$index])) {
                if (is_string(self::$callbacks[$index])) {
                    $path = $this->parseUrl(self::$callbacks[$index]);
                    $class = $this->app->parseController($path[0]);
                    if (class_exists($class)) {
                        $instance = $this->app->getObject($class);
                        $action = $path[1];
                        if (is_callable([$instance, $action])) {
                            $data = $this->app->invokeMethod($instance, $action);
                            return $this->getResponse($data);
                        } else {
                            throw new Exception("The function '{$action}' of Class '{$class}' is not exists");
                        }
                    }
                } else {
                    $this->app->getObject(self::$callbacks[$index]);
                }
            }
        }

        //查找隐式路由 
        $path = $this->parseUrl($uri);
        $class = $this->app->parseController($path[0]);
        if (class_exists($class)) {
            $instance = $this->app->getObject($class);
            $action = $path[1];
            if (is_callable([$instance, $action])) {
                $res = $this->app->invokeMethod($instance, $action);
                return $this->getResponse($res);
            } else { 
                throw new Exception("The function '{$action}' of Class '{$class}' is not exists");
            }
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    protected function getResponse($data): Response
    {
        if ($data instanceof Response) {
            $response = $data;
        } else {
            $response = Response::create($data, 'html');
        }
        return $response;
    }

    protected function parseUrl(string $url)
    {
        //默认的控制器和动作
        $controller = $action = 'index';
        $uri = parse_url($url, PHP_URL_PATH);
        $uri = trim($uri, '/');
        if (!empty($uri)) {
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
