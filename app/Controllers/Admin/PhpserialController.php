<?php
/**
 * 小工具类 > 公司服务端 SerialDefine.h 转 php/json 配置文件
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use Bootstrap\Core\LazyLog;
use Bootstrap\Core\LazyRequest;

class PhpserialController extends CommonController
{
    protected $tmpCDict = STORAGE_PATH . DS . 'cache' . DS . 'tmp' . DS . 'CDict.tmp.php';
    protected $alias = [//配置服务端key到php key的映射关系(区分大小写)
        'EquipmentSerial' => 'equipSerial',
    ];

    public function index()
    {
        return $this->view('admin.phpsearial.index');
    }

    public function toCdict(LazyRequest $request)
    {
        $content = $request->input('content');
        $content = $this->translateToCDict($content);
        return $this->ajaxReturn(true, '', ['msg' => $content]);
    }

    public function toPhpserial()
    {
        $file = $this->tmpCDict;
        $status = true;
        if (file_exists($file) && filesize($file) > 0) {
            if (!\Bootstrap\PHP_check_syntax($file)) {//生成的php文件无语法错误
                $content = $this->translateToPhpserail($file);
            } else {
                $status = false;
            }
        }
        return $this->ajaxReturn($status, '', ['msg' => $content ?? '']);
    }


    protected function translateToCDict($content)
    {
        $res = '';
        if ($content) {
            $replace = $replace2 = [];
            $pattern1 = '#enum\s([a-zA-Z]+Serial)\s+\{#';

            if (preg_match_all($pattern1, $content, $matches)) {
                if ($matches[1]) {
                    //小心这里的坑！如果你的php版本不支持匿名函数，这里会出现某种奇怪的结果
                    $replace = array_map(function ($item) {
                        if (isset($this->alias[$item])) {
                            $item = $this->alias[$item];
                        }
                        return 'public static $' . lcfirst($item) . 'Type = [';
                    }, $matches[1]);

//                    $replace = array_map([$this, 'translateTitle'], $matches[1]);
                }
            }
            $matches[0] = array_merge($matches[0], ['}']);
            $replace = array_merge($replace, [']']);
            $content = str_replace($matches[0], $replace, $content);

            $pattern2 = '#[A-Z_0-9]+\s*=\s*(?<id>[0-9]+)\s*,\s*//\s*(?<value>\S+.*\S+)#';
            if (preg_match_all($pattern2, $content, $matches2)) {
                foreach ($matches2[0] as $key => $value) {
                    $replace2 = array_map(function ($id, $value) {
                        return "{$id} => \"{$value}\",";
                    }, $matches2['id'], $matches2['value']);
                }
//                $replace2 = array_map([$this, 'translateContent'], $matches2['id'], $matches2['value']);
            }

            $content = str_replace($matches2[0], $replace2, $content);

            $content = preg_replace('#\s+[A-Z_]+[\s=0-9]*,#', '', $content);
            $content = preg_replace('#\s[A-Z_]+\s#', '', $content);
            $res = "<?php\r\nclass CDict\r\n{\r\n" . $content . "\r\n}";
        }
        file_put_contents($this->tmpCDict, $res);
        return $res;

    }

    protected function translateToPhpserail($file)
    {
        require_once $file;
        $result = [];

        $class = new \ReflectionClass(new \CDict());
        $staticpros = $class->getStaticProperties();
        if ($staticpros) {
            foreach ($staticpros as $item => $value) {
                foreach ($value as $id => $info) {
                    $result[$item][$id]["name"] = $info;
                }
            }
        }
        return $result;
    }


//    protected function translateTitle($item)
//    {
//        if (isset($this->alias[$item])) {
//            $item = $this->alias[$item];
//        }
//        return 'public static $' . lcfirst($item) . 'Type = [';
//    }

//    protected function translateContent($id, $value)
//    {
//        return "{$id} => \"{$value}\",";
//    }
}