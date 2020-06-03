<?php

namespace noone;

class App extends Container
{


    protected array $alias = [
        'app' => App::class,
        'request' => Request::class,
        'response' => Response::class,
        'cache' => Cache::class,
        'route' =>  Route::class,
    ];


    protected string $libPath = '';
    protected string $appPath = '';

    public function __construct(string $appPath = '')
    {
        $this->libPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $this->appPath =  $appPath ? rtrim($appPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $this->getAppPath();
    }

    public function getAppPath(): string
    {
        return dirname(dirname($this->libPath)) . DIRECTORY_SEPARATOR . "app".DIRECTORY_SEPARATOR;
    }

    public function run()
    {
        $request = $this->get('request');
        $this->route($request);
    }

    protected function route($request)
    {
        $this->route->loadRoutes();
        $this->route->dispatch($request);
    }

}
