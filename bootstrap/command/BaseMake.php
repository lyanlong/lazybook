<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/28 0028
 * Time: 15:43
 */

namespace Bootstrap\Command;


abstract class BaseMake implements InterfaceCli
{
    public static function run($data, $rewrite = false){
        
    }

    public static function parseFile($modelName)
    {
        $modelNameArr = array_map('ucfirst', explode(DS, str_replace('/', DS, trim($modelName,'/\\'))));
        $modelName = implode(DS, $modelNameArr);
        $modelFullName = static::$dir.DS.$modelName;
        return [
            'class' =>  array_pop($modelNameArr),
            'namespace' =>  rtrim('\\'.implode('\\', $modelNameArr),'\\'),
            'file' =>  $modelFullName,
        ];
    }

}