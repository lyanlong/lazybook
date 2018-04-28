<?php
/**
 * Created by lazy make:controller.
 */

namespace App\Controllers\Frant;


use Bootstrap\Core\LazyController;

class IndexController extends LazyController
{
    public function index()
    {
        return $this->view('frant.index.index');
    }
}