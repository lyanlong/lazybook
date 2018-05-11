<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 17:50
 */

namespace Bootstrap\Core;


class LazyException
{
    protected static $dir = 'exceptions';
    
    public static function register()
    {
        set_exception_handler([__CLASS__, 'appError']);
        is_dir(self::$dir) or mkdir(self::$dir, 0644);
    }

    public static function appError(\Throwable $e)
    {
        echo 'lazyException ourred: '.$e->getMessage();
        self::log($e->getMessage());
    }
    
    public static function log($data)
    {
        LazyLog::log(self::$dir. DS. date('Y-m-d') . '.log', $data);
    }

}