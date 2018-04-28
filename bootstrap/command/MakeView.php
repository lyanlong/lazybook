<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 20:11
 */

namespace Bootstrap\Command;


class MakeView extends BaseMake
{
    protected static $dir = APP_PATH.DS.'views';

    public static function run($modelName, $rewrite = false)
    {
        $modelFullName = str_replace('/', DS, self::$dir.DS.$modelName.'.'.TEMPLATE_EXT);
        if(!$rewrite && file_exists($modelFullName)){
            return ['status' => 0, 'msg' => "file '{$modelFullName}' already exist,rewrite it now?(Y/N)"];
        }else{
            $temp = self::templates($modelName);
            is_dir(dirname($modelFullName)) || mkdir(dirname($modelFullName), 0644, true);
            return ['status' => file_put_contents($modelFullName, $temp)];
        }
    }

    protected static function templates($modelName)
    {
        $content = <<<TEMPLATE
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$modelName</title>
</head>
<body>

</body>
</html>
TEMPLATE;
        return $content;
    }
}