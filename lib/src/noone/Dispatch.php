<?php

namespace noone;

class Dispatch
{

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }



    public function toResponse($data)
    {
        if ($data instanceof Response) {
            $response = $data;
        } else {
            $response = Response::create($data, 'html');
        }
        return $response;
    } 
}
