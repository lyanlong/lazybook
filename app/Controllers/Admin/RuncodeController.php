<?php
/**
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use Bootstrap\Core\LazyRequest;

class RuncodeController extends CommonController
{
    public function index()
    {
        return $this->view('admin.runcode.index');
    }

    public function php(LazyRequest $request)
    {
        $code = $request->input('code');
        ob_start();
        eval($code);
        $result = ob_get_clean();
        $result = str_replace('stdClass Object', 'Array', $result);

        return $this->ajaxReturn(true, '', ['result' => $result]);
    }
}