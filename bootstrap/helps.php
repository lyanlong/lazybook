<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 12:04
 */

namespace Bootstrap;
/**
 * @todo: 获取http请求类型
 */
if (!function_exists('http_method')) {
    function http_method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }
}

/**
 * @todo: 判断是否为命令行模式
 */
if (!function_exists('is_cli')) {
    function is_cli()
    {
        return (PHP_SAPI === 'cli' || defined('STDIN'));
    }
}


/**
 * @todo: 获取数据库配置信息
 */
if (!function_exists('db_config')) {
    function db_config($db_key = 'default', $var_key = '')
    {
        $data = include CONFIG_PATH . DS . 'database.php';
        return $var_key ? ($data[$db_key][$var_key] ?? '') : $data[$db_key];
    }
}

/**
 * @todo: 格式化打印原始数据
 */
if (!function_exists('dd')) {
    function dd()
    {
        echo '<pre>';
        print_r(func_get_args());
        exit();
    }
}

/**
 * @todo: 按json格式化打印原始数据
 */
if (!function_exists('ddjson')) {
    function ddjson()
    {
        echo '<pre>';
        print_r(json_encode(func_get_args(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        exit();
    }
}

/**
 * @todo: 检查文件是否有语法错误
 * @return: true for get error
 */

if(!function_exists('PHP_check_syntax')) {
    function PHP_check_syntax($file_name) {
        $cmd = "php -l $file_name";
        $res = exec($cmd);
        return "Errors parsing {$file_name}" == $res;
    }
} 