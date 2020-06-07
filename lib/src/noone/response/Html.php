<?php


namespace noone\response;

use noone\Response;

class Html extends Response
{

    public function __construct($data)
    {
        $this->data = $data;
        $this->setHeaders('Content-Type', 'text/html;charset=utf-8');
    }

    public function format($data)
    {
        return $data;
    }
}
