<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 20:12
 */

namespace Bootstrap\Command;


use Bootstrap\Core\LazyModel;

class MakeTable extends BaseMake
{
    protected static $dir = ROOT.DS.'db_migrations';
    
    public static function run($sql_file, $rewrite = false)
    {
        
        $conn = LazyModel::getInstance();
        $data = require self::$dir.DS.$sql_file.'.'.SCRIPT_EXT;
        $data = str_replace('<{{tablename}}>',DB_PREX.$sql_file, $data); //这里刻意设置表名和文件名一致
        //这里返回受影响的行数
        if($rewrite){
            $conn->exec("DROP TABLE IF EXISTS ".DB_PREX.$sql_file);
            return ['status' => ($conn->exec($data) === false) ? 0 : 1];
        }else{
            $res = $conn->exec($data);//坑点: 成功返回0，失败返回false
            if(false === $res){
                return ['status' => 0, 'msg' => "table `".DB_PREX.$sql_file."` already exist,rebuild it now?(Y/N)"];
            }else{
                return ['status' => 1];
            }
        }
    }

}