<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-16 22:21:16
 * @LastEditTime: 2020-03-18 09:52:15
 * @Description: 
 */
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('LIB_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib/src' . DIRECTORY_SEPARATOR);

function load(string $class)
{
    $path = strtr($class, '\\', DIRECTORY_SEPARATOR);
    if (0 === stripos($class, 'app')) {
        $file = APP_PATH . $path . '.php';
    } elseif (0 === stripos($class, 'noone')) {
        $file = LIB_PATH . $path . '.php';
    }
    if (file_exists($file)) {
        include $file;
        return true;
    }
}

spl_autoload_register('load');

// noone\Route::get('/', function($a){
//     echo 123 .$a;
// });

// noone\Route::$error = function(){
//     echo '99999';
// };
// noone\Route::dispatch();

new noone\Request();

exit();
