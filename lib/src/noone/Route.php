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

    public function dispatch(Request $request): Response
    {
        $path = parse_url($request->server('REQUEST_URI'), PHP_URL_PATH);
        $method = $request->server('REQUEST_METHOD');
        $route_index = array_keys(self::$routes, $path);

        $params = $this->app->request->params();
        //循环查找显示路由
        foreach ($route_index as $index) {
            if ((self::$methods[$index] == $method) && isset(self::$callbacks[$index])) {
                if (is_string(self::$callbacks[$index])) {
                    $data = $this->exec(self::$callbacks[$index], $params);
                } else {
                    $data = $this->app->invokeFunc(self::$callbacks[$index], $params);
                }
                return $this->app->dispatch->toResponse($data);
            }
        }
        //匹配隐式路由
        $data = $this->exec($path, $params);
        return $this->app->dispatch->toResponse($data);
    }

    /**
     * 解析路由
     *
     * @param string $path
     * @param array $params
     * @return void
     * @author xsm
     * @since 2020-06-19
     */
    protected function exec(string $path, array $params)
    {
        $path = $this->parseUrlPath($path);
        $class = $this->app->parseController($path[0]);
        if (class_exists($class)) {
            $instance = $this->app->make($class);
            $action = $path[1];
            if (is_callable([$instance, $action])) {
                return $this->app->invokeMethod($instance, $action, $params);
            } else {
                throw new Exception("The action '{$action}' of Class '{$class}' is not exists");
            }
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    /**
     * 解析url中的pathinfo
     *
     * @param string $path
     * @return void
     * @author xsm
     * @since 2020-06-19
     */
    protected function parseUrlPath(string $path):array
    {
        //默认的控制器和动作
        $controller = $action = 'index';
        $path = trim($path, '/');
        if ($path) {
            $urlPath = explode('/', $path);
            if (isset($urlPath[0]) && !empty($urlPath[0])) {
                if (count($urlPath) >= 3) {
                    $module = array_shift($urlPath);
                    $controller = array_shift($urlPath);
                    $controller = "{$module}/{$controller}";
                    $action = array_shift($urlPath);
                } else {
                    $controller = array_shift($urlPath);
                    $action = array_shift($urlPath) ?: 'index';
                }
            }
        }
        return [$controller, $action];
    }
}
