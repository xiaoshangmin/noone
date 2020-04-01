<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-18 09:44:58
 * @LastEditTime: 2020-03-31 18:48:42
 * @Description: 
 */

namespace noone;

class Request
{

    public $get = [];
    public $post = [];
    public $server = [];
    public $header = [];
    public $file = [];

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = file_get_contents('php://input');
        $this->server = $_SERVER;
        $this->file = $_FILES;
        if (function_exists('apache_request_headers')) {
            $header = apache_request_headers();
        } else {
            $header = [];
            $server = $_SERVER;
            foreach ($server as $key => $val) {
                if (0 === strpos($key, 'HTTP_')) {
                    $key = strtolower(substr($key, 5));
                    $header[$key] = $val;
                }
            }
        }
        $this->header = array_change_key_case($header);
    }

    public function server(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->server;
        } else {
            $name = strtoupper($name);
        }
        return $this->server[$name] ?? $default;
    }

    public function header(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->header;
        } else {
            $name = strtolower($name);
        }
        return $this->header[$name] ?? $default;
    }

    public function get(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->get;
        } else {
            $name = $name;
        }
        return $this->get[$name] ?? $default;
    }

    public function post(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->get;
        } else {
            $name = $name;
        }
        return $this->get[$name] ?? $default;
    }
}
