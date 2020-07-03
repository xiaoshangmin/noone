<?php


use noone\Route;

Route::get('/', function () {
    return 'noone';
});
Route::get('/index', 'index/index');
Route::get('/user', 'user/user/index');