<?php
/**
 * 生产测试数据
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/4/28 0028
 * Time: 10:49
 */

namespace Bootstrap\Core;


class LazyFake
{
    /**
     * @param $rownum
     * @param array $fields
     * @return array
     */

    public static function run($rownum,array $fields = []){
        $result = [];
        if($rownum < 1 || count($fields) < 1){
            return [];
        }else{
            $i = 1;
            while ($i < $rownum){
                $tmp = [];
                foreach ($fields as $key => $value){
                    @list($type, $strArg) = explode('|', $value);
                    switch ($type){
                        case '':
                            $tmp[$key] = self::createRandString($strArg);
                            break;
                        case 'timestamp':
                            $tmp[$key] = time();
                            break;
                        case 'email':
                            $tmp[$key] = self::createRandString($strArg).'@qq.com';
                            break;
                        case 'md5':
                            $tmp[$key] = md5($strArg);
                            break;
                        default:
                            $tmp[$key] = $value(mt_rand(0,100000));
                            break;
                    }
                }
                $result[]   =   $tmp;
                $i++;
            }
        }
        return $result;
    }

    /**生成随机字符串
     * version 新加功能：如需增删或修改随机字符串类型，只需修改 $tmp 配置即可，代码会自动判断处理
     * @param int $count 位数 【默认为 4 位】
     * @param null $type 0：纯数字类型；1：纯大写字母类型；2：纯小写字母类型；3：数字字母混合类型；null：随机一个类型 【可指定具体类型，默认随机一个类型】
     * @return string
     */
    public static function createRandString($count = 4, $type = null)
    {
        $count = intval($count);
        assert($count > 0, 'createRandString count error');
        $tmp = [
            48 => 57,//数字
            65 => 90,//A~Z
            97 => 122,//a~z
        ];

        $tmpIndex = array_values(array_flip($tmp)); //[48,65,97]
        $countType = count($tmp); //3
        ($type or 0 === $type) or $type = mt_rand(0, $countType); //[0,3]
        $count = intval($count);
        $randStr = '';

        if (isset($tmpIndex[$type])) {
            $key = $tmpIndex[$type];
        } else {
            $mixed = 1; //
        }
        for ($i = 0; $i < $count; $i++) {
            isset($mixed) and $key = array_rand($tmp, 1);
            $randStr .= sprintf("%c", mt_rand($key, $tmp[$key]));
        }
        return $randStr;
    }
}