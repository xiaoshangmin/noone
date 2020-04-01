<?php

namespace noone;

class App extends Container
{


    public $bind = [
        'request' => Request::class,
        'response' => Response::class,
        'cache' => Cache::class,
        'route' =>  Route::class,
    ];

    public function __construct()
    {
    }

    public function run()
    {
        $this->route();
    }

    public function route()
    {
        
    }
}
