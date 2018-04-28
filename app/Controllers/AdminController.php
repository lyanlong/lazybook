<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 19:32
 */

namespace App\Controllers;

use Bootstrap\Core\LazyRequest;
use Bootstrap\Core\LazyController;

class AdminController extends CommonController
{
    public function index(LazyRequest $request)
    {
//        var_dump($request->name);
//        var_dump($request->input());
        $name = $request->input('name');
        $age = $request->input('age');

        return $this->view('admin.index', ['name' => $name, 'age' => $age]);
    }

    public function edit()
    {
        return $this->view('admin.edit');
    }
}