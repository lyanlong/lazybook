<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 18:29
 */

namespace Bootstrap\Core;

//use Bootstrap\Core\LazyLog;

class LazyRequest
{
    protected static $model = null;
    protected static $controller = null;
    protected static $action = null;
    protected static $data = null;
    protected static $url = null;
    protected static $route = [];

//http://www.lazybook.com/admin/edit/id/12/name/3   =>  admin/edit/id/12/name/3
    public static function router($url, array $post)
    {
//        LazyLog::log('debug.log', 'LazyRequest.router.url:'.$url);
        $http_method = strtolower(\Bootstrap\http_method());
        if (isset(LazyRoute::$router[$http_method][$url])) {
            @list($model, $controllerAction) = explode('/', str_replace('\\', '/', LazyRoute::$router[$http_method][$url]));
            @list($controlle, $action) = explode('@', $controllerAction);
            $urlinfo = [$model, $controlle, $action];
        } else {
            $urlinfo = explode('/', trim($url, '/'));
        }
//        LazyLog::log('debug.log', 'LazyRequest.router.urlinfo:'.json_encode($urlinfo));
        self::$url = $url;
        self::$model = (isset($urlinfo[0]) and $urlinfo[0]) ? $urlinfo[0] : 'frant';
        self::$controller = (isset($urlinfo[1]) and $urlinfo[1]) ? $urlinfo[1] : 'index';
        self::$action = (isset($urlinfo[2]) and $urlinfo[2]) ? $urlinfo[2] : 'index';
        self::$data['get'] = self::parseGetData(array_slice($urlinfo, 3));
        self::$data['post'] = $post;

        self::dispatch();
    }

    public static function dispatch()
    {
        LazyLog::log('request_' . date('Y-m-d') . '.log', [self::$url => [self::$model,self::$controller, self::$action, self::$data]]);
        $controller = 'App\Controllers\\' . ucfirst(self::$model.'\\'.self::$controller) . 'Controller';
//        LazyLog::log('debug.log', 'LazyRequest.dispatch.urlinfo:'.$controller.'|'.strtolower(self::$action));
        self::response(call_user_func_array([new $controller, strtolower(self::$action)], [new self()]));
    }

    public static function response($html)
    {
        print_r($html);
    }

    protected static function parseGetData(array $data)
    {
        $result = [];
        for ($i = 0; $i < count($data); $i += 2) {
            isset($data[$i + 1]) and $result[$data[$i]] = $data[$i + 1];
        }
        return $result;
    }


    protected static function filter($data)
    {
        if (is_array($data)) {
            return array_map('trim', $data);
        } else {
            return trim($data);
        }
    }


    public function input($name = '', $default = '')
    {
        if ($name) {
            $result = self::$data['post'][$name] ?? (self::$data['get'][$name] ?? $default);
        } else {
            $result = array_merge(self::$data['post'], self::$data['get']);
        }
        return self::filter($result);
    }


    public function __get($name)
    {
        // TODO: Implement __get() method.
        return self::filter(self::$data['post'][$name] ?? (self::$data['get'][$name] ?? ''));
    }

}