<?php
/*
 * @@Copyright (C), 2019-2020: 甲木公司
 * @Author: xsm
 * @Date: 2020-03-16 22:21:16
 * @LastEditTime: 2020-03-16 23:18:58
 * @Description: 
 */
define('APP_PATH',__DIR__);
function load(string $class)
{
    $class = str_replace('\\','/',$class);
    $file = "{$class}.php";
    if(file_exists($file)){
        include $file;
    }
    echo $file;
}

spl_autoload_register('load');

new noone\cache\redis();
