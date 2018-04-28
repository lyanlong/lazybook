<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 21:28
 */

namespace Bootstrap\Core;


class LazyView
{
    public static function makeView($template, array $data = [])
    {
        if(file_exists($template)){
            ob_end_clean();
            ob_start();
            include $template;
            $html = ob_get_contents();
            ob_end_clean();
            return self::parseHtml($html, $data);
        }

    }

    public static function parseHtml($html, $data)
    {
        if($data){
            $template_var = '#<{{([_a-zA-Z]{1}[_a-zA-Z0-9]*)}}>#';
            if(preg_match_all($template_var, $html, $matches)){
                $complex = array_map(function($tag, $var) use ($data){
                    return $data[$var] ?? $tag;
                }, $matches[0], $matches[1]);
                $html = str_replace($matches[0], $complex, $html);
            }
        }
        return $html;
    }


}