<?php

namespace noone;


abstract class Controller
{

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function __get(string $name)
    {
        switch ($name) {
            case 'config':
                return $this->app->make('config');
                break;
            case 'request':
                return $this->app->make('reqeust');
                break;
            case 'cache':
                return $this->app->make('cache');
                break;
            case 'log':
                return $this->app->make('log');
                break;
            default:
                return false;
        }
    }
}
