<?php
define('APP_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('LIB_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'lib/src' . DIRECTORY_SEPARATOR);

//注册自动加载
require LIB_PATH . "noone/Autoloader.php";
(new noone\Autoloader())->register();

(new noone\App())->run();

