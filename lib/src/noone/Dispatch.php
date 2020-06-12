<?php

namespace noone;

class Dispatch
{

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }



    public function exec()
    {
        $path = $this->parseUri($uri);
        $class = $this->app->parseController($path[0]);
        if (class_exists($class)) {
            $instance = $this->app->resolve($class);
            $action = $path[1];
            if (is_callable([$instance, $action])) {
                return $this->app->invokeMethod($instance, $action);
            } else {
                throw new Exception("The function '{$action}' of Class '{$class}' is not exists");
            }
        } else {
            throw new Exception('class not exists:' . $class);
        }
    }

    protected function toResponse($data): Response
    {
        if ($data instanceof Response) {
            $response = $data;
        } else {
            $response = Response::create($data, 'html');
        }
        return $response;
    }
}
