<?php

namespace noone\response;

use Exception;
use noone\Response;

class Json extends Response
{

    public array $options = [
        'json_encode_param' => JSON_UNESCAPED_UNICODE
    ];

    public function __construct($data, int $code = 200)
    {
        $this->data = $data;
        $this->code = $code;
        $this->setHeaders('Content-Type', 'application/json;charset=utf-8');
    }

    public function format($data)
    {
        try {
            $data = json_encode($data, $this->options['json_encode_param']);

            if (false === $data) {
                throw new Exception(json_last_error_msg());
            }
            return $data;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
