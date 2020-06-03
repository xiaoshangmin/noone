<?php

namespace noone;

class Route
{

    public static array $methods = [];

    public static array $routes = [];

    public static array $callbacks = [];

    public static $error;

    protected App $app;

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
        $routeFile = $this->app->getAppPath() . "route" . DIRECTORY_SEPARATOR . "route.php";
        if (file_exists($routeFile)) {
            include_once $routeFile;
        }
    }

    public function dispatch(Request $request)
    {
        $uri = parse_url($request->server('REQUEST_URI'), PHP_URL_PATH);
        if ('/' != $uri) {
            $uri = rtrim($uri, '/');
        }
        $found_route = false;
        $method = $request->server('REQUEST_METHOD');
        $route_index = array_keys(self::$routes, $uri);
        foreach ($route_index as $index) {
            if ((self::$methods[$index] == $method) && isset(self::$callbacks[$index])) {
                if (is_string(self::$callbacks[$index])){

                }else
                {
                    Container::getInstance()->exec(self::$callbacks[$index]);
                }
                return;
            }
        }

        if (!$found_route){
            $this->parseUrl();
        }

        if (!$found_route) {
            if (!self::$error) {
                self::$error = function () {
                    echo "404 Not Found!";
                };
                call_user_func(self::$error);
            } elseif (is_string(self::$error)) {
                self::get($request->server('REQUEST_URI'), self::$error);
            } elseif (self::$error instanceof \Closure) {
                call_user_func(self::$error);
            }
        }
    }

    protected function parseUrl(string $url)
    {

    }
}
