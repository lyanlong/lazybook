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
Bootstrap\Core\LazyRoute::get('/', 'Frant/index@index');
Bootstrap\Core\LazyRoute::get('/login', 'Admin/Entry@loginForm');
Bootstrap\Core\LazyRoute::post('/login', 'Admin/Entry@login');
Bootstrap\Core\LazyRoute::get('/logout', 'Admin/Entry@logout');

