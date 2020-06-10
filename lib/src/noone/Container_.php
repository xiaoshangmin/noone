<?php

namespace noone;

use Exception;

class Container implements ContainerInterface
{

    protected static $instance = null;

    protected array $alias = [];

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function resolve($abstract, array $vars = [])
    {
        if ($abstract instanceof \Closure) {
            //匿名函数
            $object = $this->invokeFunc($abstract, $vars);
        } else {
            //类
            $object = $this->invokeClass($abstract, $vars);
        }
        return $object;
    }

    public function invokeFunc($func, array $vars=[])
    {
        try {
            $reflect = new \ReflectionFunction($func);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage());
        }
        $args = $this->bindParams($reflect, $vars);
        return $func(...$args);
    }

    public function invokeClass(string $class, array $vars=[])
    {
        try {
            $reflect = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage());
        }

        $construct = $reflect->getConstructor();
        $args = $construct ? $this->bindParams($construct, $vars) : [];
        return $reflect->newInstanceArgs($args);
    }

    public function invokeMethod(object $instance, string $method, array $vars=[])
    {
        try {
            $reflect = new \ReflectionMethod($instance, $method);
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage());
        }
        $args = $this->bindParams($reflect, $vars);
        return $reflect->invokeArgs($instance, $args);
    }

    public function bind($service, $provider, $singleton = false)
    {

        if ($singleton && !is_object($provider)) {
            throw new \Exception("error1");
        }

        if (!$singleton && !class_exists($provider)) {
            throw new \Exception("error2");
        }
        $this->bind[$service] = [
            'provider' => $provider,
            'singleton' => $singleton,
        ];
    }


    public function bindParams(\ReflectionFunctionAbstract $reflection, array $vars = []): array
    {
        $params = [];
        if ($reflection->getNumberOfParameters() == 0) {
            return $params;
        }
        $parameters = $reflection->getParameters();
        foreach ($parameters as $param) {
            $paramClassObj = $param->getClass();
            $paramName = $param->getName();
            //有对象参数
            if ($paramClassObj) {
                $tempVars = $vars;
                $temp = array_shift($tempVars);
                //是否传入对应对象的实例
                if ($temp instanceof $paramClassObj) {
                    $params[] = $paramClassObj;
                    $vars = array_shift($vars);
                } else {
                    $paramClassObjName = $paramClassObj->getName();
                    $params[] = $this->resolve($paramClassObjName);
                }
            } else if (isset($vars[$paramName])) {
                $params[] = $vars[$paramName];
            } elseif ($param->isDefaultValueAvailable()) {
                $defaultValue = $param->getDefaultValue();
                $params[$paramName] = $defaultValue;
            } else {
                throw new Exception("param '{$paramName}' miss");
            }
        }
        return $params;
    }

    public function __get($name)
    {
        return $this->get($name);
    }


    public function get(string $abstract)
    {
        if (isset($this->alias[$abstract])) {
            return $this->resolve($this->alias[$abstract]);
        }
        throw new \Exception('class not exists');
    }

    public function has(string $abstract)
    {
    }
}
