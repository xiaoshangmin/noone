<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-16 22:21:16
 * @LastEditTime: 2020-03-31 17:08:58
 * @Description: 
 */
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('LIB_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib/src' . DIRECTORY_SEPARATOR);

//注册自动加载
require LIB_PATH . "noone/Autoloader.php";
(new noone\Autoloader())->register();


(new noone\App())->run();
// noone\Route::get('/', function(noone\Request $req){
//     echo noone\Route::class;
// });

// noone\Route::dispatch();

// new noone\Request();

exit();
