<?php

namespace noone\cache;

interface CacheInterface
{

    public function get($key);

    public function set($key, $val);

    public function delete($key);

    public function has($key);

    public function increment($key, $value);

    public function decrement($key, $value = 1);

    public function clear();
}
