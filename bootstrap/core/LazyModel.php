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
    private $sql = '';

    private function __construct($dsn, $user, $pwd, $charset = 'utf8')
    {
        $this->db = new \PDO($dsn, $user, $pwd);
        $this->db->exec("SET NAMES {$charset}");
    }

    protected function logSql() {
        return LazyLog::log('sql.log', $this->sql);
    }
    
    public static function getInstance($dsn = '', $user = '', $pwd = '', $charset = 'utf8')
    {
        if(!$dsn && !$user && !$pwd){
            $db_config = \Bootstrap\db_config('default');
            $dsn = $db_config['dsn'];
            $user = $db_config['db_user'];
            $pwd = $db_config['db_pwd'];
            $charset = $db_config['db_charset'];
        }
        self::$instance instanceof self or self::$instance = new self($dsn, $user, $pwd, $charset);
        return self::$instance;
    }
    
    public function exec($sql)
    {
        $this->sql = $sql;
        $this->logSql();
        return $this->db->exec($sql);
    }

    public function query($sql, $type=2)
    {
        //2 => FETCH_ASSOC ;3 => FETCH_NUM
        $type == 2 || $type == 3;
        $this->sql = $sql;
        $this->logSql();
        return $this->db->query($sql)->fetchAll($type);
    }

    public function hasOne($sql)
    {
        $this->logSql();
        if(!$sql){
            return false;
        }
        return $this->db->query($sql)->rowCount() > 0;
    }



}