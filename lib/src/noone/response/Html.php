<?php


namespace noone\response;

use noone\Response;

class Html extends Response
{

    public function __construct($data,int $code=200)
    {
        $this->data = $data;
        $this->code = $code;
        $this->setHeaders('Content-Type', 'text/html;charset=utf-8');
    }

    public function format($data):string
    {
        if(is_array($data) || is_object($data)){
            $data = var_export($data,true);
        }
        return $data;
    }
}
