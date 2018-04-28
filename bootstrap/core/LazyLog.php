<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 19:54
 */

namespace Bootstrap\Core;


class LazyLog
{
    protected static $dir = STORAGE_PATH.DS.'logs';
    
    public static function log($filename, $data, $append = true)
    {
        $filename or $filename = date('Y-m-d').'_lazy_log';
        $file = self::$dir.DS.$filename;
        $content = '[ '.date('Y-m-d H:i:s').' ] '.(is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE));
        if($append){
            $size = file_put_contents($file, PHP_EOL.$content.PHP_EOL, FILE_APPEND);
        }else{
            $size = file_put_contents($file, PHP_EOL.$content);
        }
        return $size;
    }

}