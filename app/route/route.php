<?php


use noone\Route;

Route::get('/', function () {
    echo 123456798;
});
Route::get('/index','index/index');
Route::get('/user','user/user/index');