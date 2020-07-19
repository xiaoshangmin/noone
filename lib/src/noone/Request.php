<?php

namespace noone;

class Request
{

    public array $request = [];
    public array $get = [];
    public array $post = [];
    public array $input = [];
    public array $server = [];
    public array $header = [];
    public array $files = [];
    public array $cookie = [];
    protected string $controller = '';
    protected string $action = '';

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->get = $_GET;
        $this->input = file_get_contents('php://input') ?: [];
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES ?? [];
        $this->cookie = $_COOKIE;
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

    public function params():array
    {
        $method = $this->method();
        $params = [];
        switch ($method) {
            case 'GET':
                $params = $this->get();
                break;
            case 'POST':
                $params = $this->post();
                break;
            default:
                $params = [];
        }
        return $params;
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
        }
        return $this->get[$name] ?? $default;
    }

    public function post(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->post;
        }
        return $this->post[$name] ?? $default;
    }

    public function input(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->input;
        }
        return $this->input[$name] ?? $default;
    }

    public function cookie(string $name = '', string $default = '')
    {
        if (empty($name)) {
            return $this->cookie;
        }
        return $this->cookie[$name] ?? $default;
    }

    public function method(): string
    {
        return $this->server('REQUEST_METHOD') ?: 'GET';
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(string $controller)
    {
        $this->controller = $controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction(string $action)
    {
        $this->action = $action;
    }

    public function ip(): string
    {
        return $this->server('REMOTE_ADDR');
    }

    public function isCgi(): bool
    {
        return 0 === strpos(PHP_SAPI, 'cgi');
    }

    public function isCli(): bool
    {
        return PHP_SAPI == 'cli';
    }
}
