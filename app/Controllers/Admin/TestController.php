<?php
/**
 * just a test
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use Bootstrap\Core\LazyController;
use Bootstrap\Core\LazySession;

class TestController extends LazyController
{
    public function index(){
        return $this->view('admin.test.index', ['admin' => LazySession::getValue('email'), 'time' => date('A') == 'AM' ? '上午' : '下午' .date('g:i:s')]);
    }
}