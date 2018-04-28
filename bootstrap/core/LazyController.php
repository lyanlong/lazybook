<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 19:33
 */

namespace Bootstrap\Core;


class LazyController
{
    protected static $dir = APP_PATH.DS.'views';
    
    public function view($template, array $data = [])
    {
        $file = self::$dir.DS.str_replace('.', DS, $template).'.'.TEMPLATE_EXT;
        return LazyView::makeView($file, $data);
    }

    public function redirect($url){
//        LazyLog::log('debug.log', 'LazyController.redirect:'.$url);
        header('Location:'.$url);
    }

}