<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-16 22:21:16
 * @LastEditTime: 2020-03-23 22:06:21
 * @Description: 
 */
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('LIB_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib/src' . DIRECTORY_SEPARATOR);

require LIB_PATH . "noone/Autoloader.php";
(new noone\Autoloader())->register();
noone\Route::get('/', function(noone\Request $req){
    echo noone\Route::class;
});

noone\Route::dispatch();

// new noone\Request();

exit();
