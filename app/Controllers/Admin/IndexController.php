<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 15:51
 */

namespace App\Controllers\Admin;


use App\Models\FTools;
use Bootstrap\Core\LazyFake;
use Bootstrap\Core\LazySession;
use App\Models\Menus;

class IndexController extends CommonController
{
    public function index(){
        $data = [
            'admin' => LazySession::getValue('email'), 
            'time' => date('A') == 'AM' ? '上午' : '下午' .date('g:i:s'),
            'toollist'  => (new FTools())->getTools(),
        ];
        
        return $this->view('admin.index.index', $data);
    }

    public function menuTree(){
        $data = (new Menus()) -> getMenuTree(['type in (2,3)', 'status = 1']);
        return $this->ajaxReturn(true, ['children' => $data]);
    }

}