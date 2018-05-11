<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/26 0026
 * Time: 11:59
 */
header('Content-type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Chongqing');

define('DEVELOPMENT_ENV', false);//true for debug
define('SCRIPT_EXT', 'php');
define('TEMPLATE_EXT', 'lazy.php');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT',  dirname(dirname(__FILE__)));
define('BOOT_PATH', ROOT.DS.'bootstrap');
require_once BOOT_PATH.DS.'app.php';


