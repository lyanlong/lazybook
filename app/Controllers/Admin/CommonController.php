<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 18:26
 */

namespace App\Controllers\Admin;


use Bootstrap\Core\LazyController;
use Bootstrap\Core\LazyLog;
use Bootstrap\Core\LazySession;

class CommonController extends LazyController
{
    public function __construct()
    {
        if(!LazySession::getValue('email')){
            return $this->redirect('/adminlogin');
        }
    }

    public function ajaxReturn($status = false, $msg = '', $extends = [])
    {
        $res = [
            'status'    => $status,
            'data'  =>  $msg
        ];
        $extends && $res = array_merge($res, $extends);
        return json_encode($res);
    }

}