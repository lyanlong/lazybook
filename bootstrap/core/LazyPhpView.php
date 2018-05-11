<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/5/2 0002
 * Time: 17:18
 */

namespace Bootstrap\Core;


class LazyPhpView
{
    protected static $dir = STORAGE_PATH . DS . 'cache' . DS . 'tmplates';

    public static function makeView($template, array $data = [])
    {
        if ($cachefile = self::getCacheTemplate($template, CACHE_TIME)) {//有效的缓存文件
            ob_start();
            include $cachefile;
            $html = ob_get_clean();
        } else {
            if (!is_file($template)) {
                trigger_error('模板文件不存在: ' . $template);
                exit;
            }
            ob_start();
            extract($data, EXTR_OVERWRITE);
            include $template;
            $html = ob_get_clean();
            self::setCacheTemplate($template, $html);
        }
        return $html;
    }


    public static function getCacheTemplate($template, $cacheTime)
    {
        $file = self::$dir . DS . md5($template);
        return file_exists($file) && time() - filemtime($file) < $cacheTime ? $file : false;
    }

    public static function setCacheTemplate($name, $content)
    {
        $file = self::$dir . DS . md5($name);
        return file_put_contents($file, $content);
    }

}