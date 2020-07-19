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
        return $this->app->{$name};
    }
}
