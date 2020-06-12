<?php

namespace noone;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;

class Container
{

    protected static array $services = [];
    protected array $instance = []; 

    public function singleton($service, $provider = null)
    {
        $this->bind($service, $provider, true);
    }

    /**
     * 
     *
     * @param string $service 注册的服务
     * @param [type] $provider 服务的提供者
     * @param boolean $singleton 是否是单例
     * @return void
     * @author xsm
     * @since 2020-06-11
     */
    public function bind(string $service, $provider = null, $singleton = false)
    {
        if (is_null($provider)) {
            $provider = $service;
        }

        if ($singleton && !is_object($provider)) {
            throw new Exception('singleton provider must be an instance');
        }

        if (!$singleton && !class_exists($provider)) {
            throw new Exception('provider class not exists!');
        }

        self::$services[$service] = [
            'provider' => $provider,
            'singleton' => $singleton
        ];
    }

    public function make($service, array $vars = [])
    {
        if ($service instanceof Closure) {
            $object = $this->invokeFunc($service, $vars);
        } else {
            $object = $this->invokeClass($service, $vars);
        }
        return $object;
    }

    public function getProvider($service)
    {
        if (isset(self::$services[$service])) {
            return self::$services[$service]['provider'];
        }
    }

    public function invokeFunc($service, $vars = [])
    {
        $reflection = new ReflectionFunction($service);
        $args = $this->bindParams($reflection, $vars);
        return $service(...$args);
    }

    public function invokeClass($service, $vars = [])
    {
        $reflection = new ReflectionClass($service);
        $construct = $reflection->getConstructor();
        $args = $construct ? $this->bindParams($construct, $vars) : [];
        return $reflection->newInstanceArgs($args);
    }

    public function bindParams(ReflectionFunctionAbstract $reflection, array $vars = [])
    {
        $params = [];
        if (0 == $reflection->getNumberOfParameters()) {
            return $params;
        }

        $parameters = $reflection->getParameters();

        foreach ($parameters as $param) {
            $class = $param->getClass();
            $paramName = $param->getName();
            //有对象参数
            if ($class) {
                if (isset($vars[$paramName]) && $vars[$paramName] instanceof $class) {
                    $params[] = $vars[$paramName];
                } else {
                    $params[] = $this->make($class);
                }
            } elseif (isset($vars[$paramName])) {
                $params[] = $vars[$paramName];
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new Exception("variable '{$paramName}' miss");
            }
        }
        return $params;
    }
}
