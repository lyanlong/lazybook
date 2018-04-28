<?php

/*
|--------------------------------------------------------------------------
| 后台路由
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Bootstrap\Core\LazyRoute::get('/', 'Entry@loginForm');
Bootstrap\Core\LazyRoute::get('/login', 'Entry@loginForm');
Bootstrap\Core\LazyRoute::post('/login', 'Entry@login');
Bootstrap\Core\LazyRoute::get('/logout', 'Entry@logout');

