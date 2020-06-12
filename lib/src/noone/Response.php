<?php


namespace noone;

use InvalidArgumentException;

abstract class Response
{

    public array $headers = [];

    public array $options = [];

    public int $code = 200;

    public $data = null;

    public function output($data)
    {
        echo $data;
    }

    public static function create($data, string $type = 'json', int $code = 200): Response
    {
        $class = "\\noone\\response\\" . ucfirst(strtolower($type));
        return Container::getInstance()->resolve($class, ['data' => $data, $code]);
    }

    public function send()
    {
        $this->handleHeaders();
        $data = $this->getData();
        $data = $this->format($data);
        $this->output($data);
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }

    public function setHeaders(string $key, string $content)
    {
        $this->headers[$key] = $content;
    }

    protected function handleHeaders()
    {
        if (!headers_sent() && !empty($this->headers)) {
            http_response_code($this->code);
            foreach ($this->headers as $key => $content) {
                header("{$key}:{$content}");
            }
        }
    }

    public function getData()
    {
        $content = $this->data;
        if (is_object($content) && !is_callable($content, '__toString')) {
            throw new InvalidArgumentException('params type error ' . gettype($content));
        }
        return $content;
    }

    public function setCode(int $code)
    {
        $this->code = $code;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function toResponse($data)
    {
        if ($data instanceof self) {
            $response = $data;
        } else {
            $response = self::create($data, 'html');
        }
        return $response;
    }

    abstract protected function format($data);
}
