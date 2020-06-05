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
    protected string $routePath = '';
    protected string $rootPath = '';
    protected string $namespace = 'app';

    public function __construct(string $rootPath = '')
    {
        $this->libPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $this->rootPath    = $rootPath ? rtrim($rootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $this->getDefaultRootPath();
        $this->appPath =  $this->rootPath. DIRECTORY_SEPARATOR . 'app';
        $this->routePath = $this->rootPath . 'route' . DIRECTORY_SEPARATOR;
    }

    public function getDefaultRootPath()
    {
        return dirname(dirname($this->libPath)) . DIRECTORY_SEPARATOR ;
    }

    public function getAppPath(): string
    {
        return $this->appPath;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function parseController(string $path)
    {
        $controller = ucwords(str_replace('/',' ',$path));
        $controller = str_replace(' ','\\',$controller);
        return $this->namespace.'\\controller\\'.$controller;
    }
    
    public function run()
    {
        $this->route();
    }

    protected function route()
    {
        $this->route->loadRoutes();
        $this->route->dispatch();
    }

}
