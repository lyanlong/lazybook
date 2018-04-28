<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 20:14
 */
namespace Bootstrap\Command;

interface InterfaceCli
{
    public static function run($data, $rewrite = false);

    public static function parseFile($modelName);
}