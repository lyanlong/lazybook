<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 10:44
 */

namespace App\Controllers\Admin;


use App\Models\Users;
use Bootstrap\Core\LazyController;
use Bootstrap\Core\LazyRequest;
use Bootstrap\Core\LazySession;

class EntryController extends LazyController
{
    public function loginForm()
    {
        return $this->view('admin.entry.loginForm');
    }
    
    public function login(LazyRequest $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if($email == 'test@gmail.com' && $password == 'test'){
            LazySession::mutiSet([
                ['key' => 'email', 'value' => $email, 'expired' => 20 * 60],
                ['key' => 'password', 'value' => $password, 'expired' => 20 * 60],
                ['key' => 'test', 'value' => 'nerver expired', 'expired' => -1],
            ]);
            exit(json_encode(['status' => true, 'url' => '/admin/index/index']));
        }else{
            exit(json_encode(['status' => false]));
        }
    }
    
    public function logout()
    {
        LazySession::clear();
        return $this->redirect('/login');
    }

}