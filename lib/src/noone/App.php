<?php

namespace noone;

class App extends Container
{


    protected $bind = [
        'app' => App::class,
        'request' => Request::class,
        'response' => Response::class,
        'cache' => Cache::class,
        'route' =>  Route::class,
    ];


    protected $appPath = '';

    public function __construct(string $appPath = '')
    {
        $this->appPath =  $appPath ? rtrim($appPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $this->getDefaultAppPath();
    }

    protected function getDefaultAppPath(): string
    {
        return dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "app";
    }

    public function run()
    {
        $this->route();
    }

    public function route()
    {
        $this->route->loadRoutes();
    }

    public function FunctionName()
    {
        # code...
    }
}
