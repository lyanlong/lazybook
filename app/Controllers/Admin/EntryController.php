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
        $model = new Users();
        if($model->hasOne(["name = '{$email}'"])){
            $roleinfo = $model->getOne(['*'], ["name = '{$email}'"]);
            if(md5($password) == $roleinfo[0]['password']){
                LazySession::mutiSet([
                    ['key' => 'email', 'value' => $email, 'expired' => 12 * 60 * 60],
                    ['key' => 'password', 'value' => $password, 'expired' => 12 * 60 * 60],
                    ['key' => 'test', 'value' => 'nerver expired', 'expired' => -1],
                ]);
                exit(json_encode(['status' => true, 'url' => '/admin/index/index']));
            }else{
                exit(json_encode(['status' => false, 'msg' => '用户名或密码错误']));
            }
        }else{
            exit(json_encode(['status' => false, 'msg' => '用户不存在']));
        }
    }
    
    public function logout()
    {
        LazySession::clear();
        return $this->redirect('/adminlogin');
    }

}