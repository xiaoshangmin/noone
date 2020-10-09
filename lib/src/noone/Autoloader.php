<?php
/**
 * 自动加载类
 */
namespace noone;

class Autoloader
{


    public function __construct()
    {
    }

    public function register()
    {
        spl_autoload_register([$this, 'loadClass'], true, true);
    }

    public function loadClass(string $class): bool
    {
        $path = strtr($class, '\\', DIRECTORY_SEPARATOR);
        if (0 === strpos($path, 'app')) {
            $file = APP_PATH . "{$path}.php";
        } else {
            $file = LIB_PATH . "{$path}.php";
        }

        if (file_exists($file)) {
            include $file;
            return true;
        }
        return false;
    }
}
