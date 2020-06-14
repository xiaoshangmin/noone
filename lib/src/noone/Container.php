<?php

namespace noone;

use InvalidArgumentException;
use ReflectionFunctionAbstract;
use Closure;

class Container implements ContainerInterface
{

    protected static $instance = null;

    protected $instances = [];

    protected array $alias = [];

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function make($abstract, array $vars = [])
    {
        $abstract = $this->getAlias($abstract);
        if ($abstract instanceof Closure) {
            //匿名函数
            $object = $this->invokeFunc($abstract, $vars);
        } else {
            //类
            if (isset($this->instances[$abstract])) {
                $object = $this->instances[$abstract];
            } else {
                $object = $this->invokeClass($abstract, $vars);
                $this->instances[$abstract] = $object;
            }
        }
        return $object;
    }


    public function getAlias($abstract)
    {
        if (isset($this->alias[$abstract])) {
            return $this->alias[$abstract];
        }
        return $abstract;
    }

    public function invokeFunc($func, array $vars = [])
    {
        try {
            $reflect = new \ReflectionFunction($func);
        } catch (\ReflectionException $e) {
            throw new \Exception("function not exists ($func}() " . $e->getMessage());
        }
        $args = $this->bindParams($reflect, $vars);
        return $func(...$args);
    }

    public function invokeClass(string $class, array $vars = [])
    {
        try {
            $reflect = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw new \Exception('class not exists ' . $e->getMessage());
        }

        $construct = $reflect->getConstructor();
        $args = $construct ? $this->bindParams($construct, $vars) : [];
        return $reflect->newInstanceArgs($args);
    }

    public function invokeMethod(object $instance, string $method, array $vars = [])
    {
        try {
            $reflect = new \ReflectionMethod($instance, $method);
        } catch (\ReflectionException $e) {
            throw new \Exception("method not exists " . $e->getMessage());
        }
        $args = $this->bindParams($reflect, $vars);
        return $reflect->invokeArgs($instance, $args);
    }


    public function bindParams(ReflectionFunctionAbstract $reflection, array $vars = []): array
    {
        $params = [];
        if ($reflection->getNumberOfParameters() == 0) {
            return $params;
        }
        $this->formatVars($vars);
        $parameters = $reflection->getParameters();
        foreach ($parameters as $index => $param) {
            $paramClassObj = $param->getClass();
            $paramName = $param->getName();

            //有对象参数
            if ($paramClassObj) {
                //是否传入对应对象的实例
                if (isset($vars[$index]) && $vars[$index] instanceof $paramClassObj) {
                    $params[] = $paramClassObj;
                } else {
                    $paramClassObjName = $paramClassObj->getName();
                    $params[] = $this->make($paramClassObjName);
                }
            } elseif (isset($vars[$index])) {
                $params[] = $vars[$index];
            } else if (isset($vars[$paramName])) {
                $params[] = $vars[$paramName];
            } elseif ($param->isDefaultValueAvailable()) {
                $defaultValue = $param->getDefaultValue();
                $params[] = $defaultValue;
            } else {
                throw new InvalidArgumentException("variable '{$paramName}' miss");
            }
        }
        return $params;
    }

    protected function formatVars(array &$vars = [])
    {
        $index = 0;
        foreach ($vars as $key => $var) {
            if (is_numeric($key)) {
                $vars[$index] = $var;
            } else {
                $vars[$key] = $var;
            }
            $index++;
        }
    }

    public function __get($name)
    {
        return $this->get($name);
    }


    public function get(string $abstract)
    {
        if (isset($this->alias[$abstract])) {
            return $this->make($this->alias[$abstract]);
        }
        throw new \Exception('class not exists');
    }

    public function has(string $abstract): bool
    {
        return isset($this->alias[$abstract]);
    }
}
