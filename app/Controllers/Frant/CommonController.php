<?php
/**
 * Created by lazy make:controller.
 */

namespace App\Controllers\Frant;


use Bootstrap\Core\LazyController;

class CommonController extends LazyController
{
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