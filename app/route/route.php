<?php


use noone\Route;

Route::get('/', function (noone\Request $req, $b = 123, $c) {
    return $c + $b;
});
Route::get('/index', 'index/index');
Route::get('/user', 'user/user/index');