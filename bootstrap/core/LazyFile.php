<?php
/**
 * 文件上传类
 * Created by PhpStorm.
 * User: liyanlong
 * Date: 2018/5/4 0004
 * Time: 14:23
 */

namespace Bootstrap\Core;


class LazyFile
{
    /**判断文件类型
     * @param $file
     * @return mixed
     */
    public static function getFileType($file)
    {
        $file = IS_WIN ? iconv('UTF-8', 'GBK', $file) : $file; //到 windows 上去查找文件内容
        $handle=finfo_open(FILEINFO_MIME_TYPE);//This function opens a magic database and returns its resource. 
        $fileInfo=finfo_file($handle,$file);// Return information about a file
        finfo_close($handle);
        return $fileInfo;
    }
    
    /**
     * 创建目录
     * @param $dir
     * @param int $code
     * @return bool
     */
    public static function makedir($dir, $code = 0644)
    {
        $dir = IS_WIN ? iconv('UTF-8', 'GBK', $dir) : $dir; //存到 windows 上
        return mkdir($dir, $code, true);
    }


    /**
     * 搜索目录
     * @param $path
     * @param $files
     * @param bool $utf8toGkb
     */
    public static function searchDir($path, &$files, $utf8toGkb = false)
    {
        $utf8toGkb && $path = iconv('UTF-8', 'GBK', $path); //在 windows 上查找,注意如果是在windows系统上则只有第一次调用时需转换为 GBK 编码，后续递归子目录时的 path 参数的值是在windows 系统上查找出来的，所以本已经是 GBK 编码了
        $path = realpath($path);
        if (is_dir($path)) {
            $dp = dir($path);
            while ($file = $dp->read()) {
                if ($file !== '.' && $file !== '..') {
                    self::searchDir($path . '/' . $file, $files);
                }
            }
            $dp->close();
        }
        if (is_file($path)) {
            //win下文件名为 GBK 编码，要想在 utf8 的网页中正常显示需要将文件名转换为utf8编码
            $files[] = IS_WIN ? iconv('GBK', 'UTF-8', $path) : $path; //到 utf8 网页上打印文件列表
        }
    }


    /**
     * 获取文件内容
     * @param $filename
     * @return string
     */
    public static function getFile($file)
    {
        if(false !== stripos(self::getFileType($file), 'image')){//图片文件直接返回相对地址
            return ['content' =>   substr($file, strlen(ROOT.DS.'public')), 'type' => 'image'];
        }

        $file = IS_WIN ? iconv('UTF-8', 'GBK', $file) : $file; //到 windows 上去查找文件内容
        $content = @file_get_contents($file);
        //PHP中的 json_encode 函数只限编码UTF-8的数据，当转换GBK或者GB2312等编码的数据时会转为NULL。这里利用这一特性来判断出 gbk 编码的文件并将其内容转换为 utf8 编码输出
        if($content && !json_encode($content)){
            $content = iconv('GBK', 'UTF-8', $content);
        }
        return ['content' => $content, 'type' => 'string'];
    }

    /**
     * 创建文件
     * @param $filename
     * @param $content
     * @return bool|int
     */
    public static function setFile($filename, $content)
    {
        if(!$filename){
            return false;
        }
        $file = IS_WIN ? iconv('UTF-8', 'GBK', $filename) : $filename;
        $content || $content = self::initEmptyFile();
        return file_put_contents($file, $content);
    }

    public static function uploadFile($path, $file)
    {
        $path = IS_WIN ? iconv('UTF-8', 'GBK', $path) : $path;
        if(realpath($path)){
            $name = IS_WIN ? iconv('UTF-8', 'GBK', $file['name']) : $file['name'];
            return move_uploaded_file($file['tmp_name'], $path.DS.$name);
        }
        return false;
    }

    public static function delFile($path, array $files)
    {
        $flag = true;
        $path = IS_WIN ? iconv('UTF-8', 'GBK', $path) : $path;
        if(realpath($path)){
            foreach ($files as $file){
                $name = IS_WIN ? iconv('UTF-8', 'GBK', $file) : $file;
                if(!unlink($path.DS.$name)){
                    $flag = false;
                }
            }
        }else{
            $flag = false;
        }
        return $flag;
    }

    public static function initEmptyFile()
    {
        $author = LazySession::getValue('email');
        $date = date('Y/m/d H:i:s');
        return <<<EOL
 * Created by Lazybook.
 * Author: $author
 * DateTime: $date
EOL;
    }
}