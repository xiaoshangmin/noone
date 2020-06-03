<?php

namespace noone;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;

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

    public function exec($abstract)
    {
        if ($abstract instanceof Closure) {
            //匿名函数
            $object = $this->invokeFunc($abstract);
        } else {
            //类
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
        } catch (ReflectionException $e) {
            throw new Exception($e->getMessage());
        }

        $construct = $reflect->getConstructor(); 
//        $params = $construct ? $this->getParams($construct) : [];
        $args = $this->bindParams($construct);
        return $reflect->newInstanceArgs($args);
    }


    public function bind($service, $provider, $singleton = false)
    {

        if ($singleton && !is_object($provider)) {
            throw new Exception("error1");
        }

        if (!$singleton && !class_exists($provider)) {
            throw new Exception("error2");
        }
        $this->bind[$service] = [
            'provider' => $provider,
            'singleton' => $singleton,
        ];
    }


    public function bindParams(\ReflectionFunctionAbstract $reflection)
    {
        $params = [];
        $parameters = $reflection->getParameters();
        foreach ($parameters as $param) {
            $paramClassObj = $param->getClass();
            if ($paramClassObj) {//有对象参数
                $paramClassObjName = $paramClassObj->getName();
//                //已经有绑定记录
//                if (array_key_exists($paramName, $this->bind)) {
//                    if ($this->bind[$paramName]['singleton']) {
//                        $params['params'][] = $this->bind[$paramClassName]['provider'];
//                    } else {
//                        $params['params'][] = static::getInstance($this->bind[$paramClassName]['provider']);
//                    }
//                } else{
//                    //没有绑定的记录
//                    $params['params'][] = $this->exec($paramClassName);
//                }
                $params[] = $this->exec($paramClassObjName);

            }else {
                $paramName = $param->getName();

                $defaultValue  = '';
                if ($param->isDefaultValueAvailable()) {
                    $defaultValue = $param->getDefaultValue();
                }
//                $type = $param->getType()->getName();
                $params[$paramName] = $defaultValue;
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
            return $this->exec($this->alias[$abstract]);
        }
        throw new \Exception('class not exists');
    }

    public function has(string $abstract)
    {
        
    }
}
