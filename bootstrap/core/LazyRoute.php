<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 16:07
 */

namespace Bootstrap\Core;


class LazyRoute
{
    public static $router = [];
    
    protected static $http_methods = ['ANY', 'GET', 'POST'];

//    public static function get($key, $uri)
//    {
//        self::$router['get'][$key] = $uri;
//    }
//
//    public static function post($key, $uri)
//    {
//    }
//
//    public static function any($key, $uri)
//    {
//    }
    
    
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        if(in_array(strtoupper($name), self::$http_methods)){
            self::$router[$name][trim($arguments[0], '/')] = $arguments[1];
        }
    }

}