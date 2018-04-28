<?php
/**
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/27 0027
 * Time: 20:12
 */

namespace Bootstrap\Command;


class MakeModel extends BaseMake
{
    protected static $dir = APP_PATH.DS.'Models';

    public static function run($modelName, $rewrite = false)
    {
        list($class, $namespace, $file) = array_values(parent::parseFile($modelName));
        $fullFile = $file.'.'.SCRIPT_EXT;
        
        if(!$rewrite && file_exists($fullFile)){
            return ['status' => 0, 'msg' => "file '{$fullFile}' already exist,rewrite it now?(Y/N)"];
        }else{
            $temp = self::templates($class, $namespace);
            is_dir(dirname($fullFile)) || mkdir(dirname($fullFile), 0644, true);
            return ['status' => file_put_contents($fullFile, $temp)];
        }
    }

    protected static function templates($class, $namespace)
    {
        $content = <<<TEMPLATE
<?php
/**
 * Created by lazy make:model.
 */

namespace App\Models$namespace;


class $class
{

}
TEMPLATE;
        return $content;
    }
}