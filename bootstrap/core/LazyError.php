<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 14:23
 */

namespace Bootstrap\Core;

use Bootstrap\Core\LazyException;
use Bootstrap\Core\LazyLog;
class LazyError
{
    protected static $dir = 'errors';
    
    public static function register()
    {
        set_error_handler([__CLASS__, 'appError']);
        is_dir(self::$dir) or mkdir(self::$dir, 0644);
    }
    
    public static function appError($errno, $errstr, $errfile, $errline, $errcontext)
    {
        echo 'lazyError ourred: '.$errstr;
        self::log('lazyError ourred: '.$errstr);
    }

    public static function log($data)
    {
        LazyLog::log(self::$dir. DS. date('Y-m-d') . '.log', $data);
    }
}