<?php

namespace noone;

use ArrayAccess;


class Config implements ArrayAccess
{

    protected array $items = [];

    public function bootstrap(App $app)
    {
        $this->loadConfigFiles($app);
    }

    public function loadConfigFiles(App $app)
    {
        $configPath = $app->getConfigPath();
        $files = glob($configPath . '*.php');
        foreach ($files as $path) {
            $key = basename($path, '.php');
            $this->items[$key] = require $path;
        }
    }

    public function set(string $key, $value)
    {
        $keys = explode('.', $key);
        $tempArr = &$this->items;
        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($tempArr[$key])) {
                $this->items[$key] = [];
            }
            $tempArr = &$this->items[$key];
        }
        $tempArr[array_shift($keys)] = $value;
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->items;
        foreach ($keys as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return $default;
            }
        }
        return $value;
    }

    public function has(string $key)
    {
        $keys = explode('.', $key);
        $value = $this->items;
        foreach ($keys as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return false;
            }
        }
        return true;
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset)
    {
    }
}
