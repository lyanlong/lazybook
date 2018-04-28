<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 11:45
 * 当启动一个Session会话时，会生成一个随机且唯一的session_id，也就是Session的文件名，此时session_id存储在服务器的内存中，当关闭页面时此id会自动注销，重新登录此页面，会再次生成一个随机且唯一的id。
 */

namespace Bootstrap\Core;


class LazySession
{
    protected static $dir = STORAGE_PATH . DS . 'sessions';

    public static function initSession()
    {
        session_save_path(self::$dir);
        session_start();
    }

    /**
     * 单session设置
     * @param $key      键
     * @param $value    值
     * @param int $expired (有效时间（单位 秒）: -1 => 永不过期)
     * @return true
     */
    public static function set($key, $value, $expired = -1)
    {
        $_SESSION[$key] = [
            'key' => $key,
            'value' => $value,
            'expire_time' => $expired < 0 ? '' : time() + $expired,
        ];
        return true;
    }

    /**
     * session批量设置
     * @param array $data [ ['key'=>'','value'=>'','expired'=>''], [] ]
     * @return true
     */
    public static function mutiSet(array $data)
    {
        foreach ($data as $value) {
            self::set($value['key'], $value['value'], $value['expired'] ?? -1);
        }
        return true;
    }


    /**
     * 获取未过期的session配置
     * @param string $key ( $key == '' 时获取所有未过期的session)
     * @return string [ 'key'=>['value'=>'', 'expired'=>''] ]
     */
    public static function get($key = '')
    {
        $result = [];
        if ($key) {
            if (!self::isExpired($key)) {
                $result = $_SESSION[$key];
            }
        } else {
            $result = array_filter($_SESSION, function ($item) {
                return self::isExpired($item['key']);
            });
        }
        return $result;
    }

    /**
     * 获取未过期的session值
     * @param string $key ( $key == '' 时获取所有session值)
     * @return array|string 'value' | [ 'key'=>'value'，'key'=>'value']
     */
    public static function getValue($key = '')
    {
        if ($key) {
            $result = !self::isExpired($key) ? $_SESSION[$key]['value'] : '';
        } else {
            $result = [];
            array_map(function ($item) use (&$result) {
                return !self::isExpired($item['key']) and $result[$item['key']] = $item['value'];
            }, $_SESSION);
        }
        return $result;
    }

    /**
     * 判断指定session是否过期
     * @param $key
     * @return bool
     */
    public static function isExpired($key)
    {
        return !isset($_SESSION[$key]['expire_time']) || ($_SESSION[$key]['expire_time'] < time() && $_SESSION[$key]['expire_time'] != '');
    }


    public static function clear(){
        session_destroy();
    }

}