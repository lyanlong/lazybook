<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 14:50
 */
define('VENDER_PATH', ROOT . DS . 'vender');
define('CONFIG_PATH', ROOT . DS . 'config');
define('APP_PATH', ROOT . DS . 'app');
define('STORAGE_PATH', ROOT . DS . 'public'. DS.'storage');
define('ROUTE_PATH', ROOT . DS . 'routes');
define('CACHE_TIME', 0 * 60);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false); //true => windows os
require_once 'helps.php';

function setReporting($debug = false, $error_log){
    error_reporting(E_ALL);
    if ($debug == true) {//开发模式下回显所有错误信息
        ini_set('display_errors','On');
    } else {//非开发模式下关闭错误回显并使用文件记录所有错误
        ini_set('display_errors','Off');
        ini_set('log_errors','On');
        ini_set('error_log',$error_log);
    }
}

function autoload_class_file($class)
{
    $lastIndex = strrpos($class, DS);
    $class = strtolower(substr($class, 0, $lastIndex + 1)). ucfirst(substr($class, $lastIndex + 1));
    $classFile = ROOT. DS. $class . '.' . SCRIPT_EXT;
    if (file_exists($classFile)) {
        require_once $classFile;
    }
}

//ini_set('display_errors',DEVELOPMENT_ENV ? 'On' : 'Off');
// 1.错误设置
setReporting(DEVELOPMENT_ENV, STORAGE_PATH.DS.'logs'.DS.'error_'.date('Y-m-d').'.log');

//2.自动加载
spl_autoload_register('autoload_class_file');

//3. 设置异常处理
//\Bootstrap\Core\LazyException::register();
//set_exception_handler('auto_exception');

//4. 设置错误处理
//\Bootstrap\Core\LazyError::register();

//5.开启session
\Bootstrap\Core\LazySession::initSession();

require_once ROUTE_PATH.DS.'web.php';
//路由转发
call_user_func_array(['Bootstrap\Core\LazyRequest', 'router'], [$_GET['url'] ?? '', $_POST]);
