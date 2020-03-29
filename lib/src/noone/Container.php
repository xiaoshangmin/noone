<?php

namespace noone;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;

class Container implements ContainerInterface
{

    protected static $instance;

    protected static $bind = [];

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
            throw new Exception($e->getMessage());
        }

        $construct = $reflect->getConstructor();
        $params = $construct ? static::getParams($construct) : [];
        return $reflect->newInstanceArgs($params);
    }


    public static function bind($service, $provider, $singleton = false)
    {
        if ($singleton && !is_object($provider)) {
            throw new Exception("error1");
        }

        if (!$singleton && !class_exists($provider)) {
            throw new Exception("error2");
        }
        static::$bind[$service] = [
            'provider' => $provider,
            'singleton' => $singleton,
        ];
    }

    public static function getParams(\ReflectionFunctionAbstract $reflection)
    {
        $params['params'] = [];
        $params['default'] = [];

        $parameters = $reflection->getParameters();
        foreach ($parameters as $key => $param) {
            $param_class = $param->getClass();
            if ($param_class) {
                $param_class_name = $param_class->getName();
                if (array_key_exists($param_class_name, static::$bind)) {
                    if (static::$bind[$param_class_name]['singleton']) {
                        $params['params'][] = static::$bind[$param_class_name]['provider'];
                    } else {
                        $params['params'][] = static::getInstance(static::$bind[$param_class_name]['provider']);
                    }
                } else {
                    $paramName = $param->getName();
                    if ($param->isDefaultValueAvailable()) {
                        $defaultValue = $param->getDefaultValue();
                        $params['default'][$paramName] = $defaultValue;
                    }
                    $params['params'][] = $paramName;
                }
            }
        }
        return $params;
    }

    public function __get($name)
    {
        $this->get($name);
    }


    public function get(string $abstract)
    {
        if (isset($this->bind[$abstract])) {
            $this->exec($this->bind[$abstract]);
        }
    }

    public function has(string $id)
    {
    }
}
