<?php
/**
 * Created by lazy make:controller.
 */

namespace App\Controllers\Frant;


use App\Models\Menus;

class IndexController extends CommonController
{
    //使用传统模板方式
    public function index()
    {
        return $this->view('frant.index.index');
    }

    public function menuTree(){
        try{
            $status = true;
            $data['children'] = (new Menus()) -> getMenuTree(['type in (1,3)', 'status = 1']);
        }catch (\Exception $e){
            $status = false;
            $data = $e->getMessage();
        }
        return $this->ajaxReturn($status, $data);
    }
    
}