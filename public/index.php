<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-16 22:21:16
 * @LastEditTime: 2020-03-22 23:19:12
 * @Description: 
 */
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('LIB_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib/src' . DIRECTORY_SEPARATOR);

require LIB_PATH . "noone/Autoloader.php";
(new noone\Autoloader())->register();
noone\Route::get('/', function(noone\Request $req){
    $get = $req->get();
    print_r($get);
});

// noone\Route::$error = function(){
//     echo '99999';
// };
noone\Route::dispatch();

// new noone\Request();

exit();
