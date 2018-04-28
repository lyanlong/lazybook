<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 14:23
 */

namespace Bootstrap\Core;


class LazyError
{
    protected static $dir = STORAGE_PATH.DS.'logs'.DS.'errors';
    
    public static function register()
    {
        set_error_handler([__CLASS__, 'appError']);
        is_dir(self::$dir) or mkdir(self::$dir, 0644);
    }
    
    public static function appError($errno, $errstr, $errfile, $errline, $errcontext)
    {
        echo 'lazyException ourred: '.$errstr;
    }

    public static function log($data)
    {
        file_put_contents(self::$dir. DS. date('Y-m-d') . '.log', is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE).PHP_EOL, FILE_APPEND);
    }
}