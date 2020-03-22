<?php

namespace noone;

use Closure;
use ReflectionClass;
use ReflectionFunction;

class Container implements ContainerInterface
{

    protected static $instance;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function exec($abstract)
    {
        if ($abstract instanceof Closure) {
            $object = $this->invokeFunc($abstract);
        } else {
            $object = $this->invokeClass($abstract);
        }
        return $object;
    }

    public function invokeFunc($func)
    {
        $reflect = new ReflectionFunction($func);
        $parameters = $reflect->getParameters();
        $args = [];
        foreach ($parameters as $key => $param) {
            $class = $param->getClass();
            if ($class) {
                $args[] = $this->invokeClass($class->getName());
            }
        }
        return $func(...$args);
    }

    public function invokeClass(string $class)
    {
        try {
            $reflect = new ReflectionClass($class);
            return $reflect->newInstanceArgs();
        } catch (\ReflectionException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function get(string $id)
    {
    }

    public function has(string $id)
    {
    }
}
