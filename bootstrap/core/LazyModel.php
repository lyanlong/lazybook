<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/28 0028
 * Time: 10:30
 */

namespace Bootstrap\Core;


class LazyModel
{
    public static $instance = null;
    private $db = null;

    private function __construct($dsn, $user, $pwd, $charset = 'utf-8')
    {
        $this->db = new \PDO($dsn, $user, $pwd);
        $this->db->exec("SET NAMES {$charset}");
    }


    public static function getInstance($dsn = '', $user = '', $pwd = '', $charset = 'utf-8')
    {
        if(!$dsn && !$user && !$pwd){
            $db_config = \Bootstrap\db_config('default');
            $dsn = $db_config['dsn'];
            $user = $db_config['db_user'];
            $pwd = $db_config['db_pwd'];
        }
        self::$instance instanceof self or self::$instance = new self($dsn, $user, $pwd, $charset);
        return self::$instance;
    }
    
    public function exec($sql)
    {
        return $this->db->exec($sql);
    }



}