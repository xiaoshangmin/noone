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
        'log' => Log::class,
        'dispatch' => Dispatch::class,
    ];

    protected string $libPath = '';
    protected string $appPath = '';
    protected string $routePath = '';
    protected string $rootPath = '';
    protected string $runtimePath = '';
    protected string $namespace = 'app';

    public function __construct(string $rootPath = '')
    {
        $this->libPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $this->rootPath    = $rootPath ? rtrim($rootPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $this->getDefaultRootPath();
        $this->appPath =  $this->rootPath . 'app' . DIRECTORY_SEPARATOR;
        $this->routePath = $this->appPath . 'route' . DIRECTORY_SEPARATOR;
        $this->runtimePath = $this->appPath . 'runtime' . DIRECTORY_SEPARATOR;
        $this->instances['noone\App'] = $this;
        date_default_timezone_set('Asia/Shanghai');
    }

    public function getDefaultRootPath()
    {
        return dirname(dirname($this->libPath)) . DIRECTORY_SEPARATOR;
    }

    public function getAppPath(): string
    {
        return $this->appPath;
    }

    public function getRoutePath(): string
    {
        return $this->routePath;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getRuntimePath(): string
    {
        return $this->runtimePath;
    }
    
    public function parseController(string $path)
    {
        $controller = ucwords(str_replace('/', ' ', $path));
        $controller = str_replace(' ', '\\', $controller);
        return $this->namespace . '\\controller\\' . $controller;
    }

    public function run()
    {
        //注册异常处理
        $this->make(Exceptions::class)->bootstrap($this);
        //解析路由并返回数据
        $this->route()->send();
    }

    protected function route()
    {
        //加载显示路由
        $this->route->loadRoutes();
        //分发
        return $this->route->dispatch($this->request);
    }
}
