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
            LazyLog::log('debug.log', 'Common.redirect:login');
            return $this->redirect('/login');
        }
    }

}