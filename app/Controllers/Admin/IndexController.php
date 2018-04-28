<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 15:51
 */

namespace App\Controllers\Admin;


use Bootstrap\Core\LazyFake;
use Bootstrap\Core\LazySession;

class IndexController extends CommonController
{
    public function index(){
        return $this->view('admin.index.index');
    }

}