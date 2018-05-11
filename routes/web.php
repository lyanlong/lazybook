<?php

/*
|--------------------------------------------------------------------------
| 后台路由
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/
Bootstrap\Core\LazyRoute::get('/', 'Frant/index@index');
Bootstrap\Core\LazyRoute::get('/adminlogin', 'Admin/Entry@loginForm');
Bootstrap\Core\LazyRoute::post('/adminlogin', 'Admin/Entry@login');
Bootstrap\Core\LazyRoute::get('/adminlogout', 'Admin/Entry@logout');
Bootstrap\Core\LazyRoute::get('/test', 'Admin/Test@index');

