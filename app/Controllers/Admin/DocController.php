<?php
/**
 * 文档文件内容控制器
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use Bootstrap\Core\LazyFile;
use Bootstrap\Core\LazyRequest;

class DocController extends CommonController
{
    /**
     * 查阅指定文档 [通过搜索查看、通过点击查看]
     * @param LazyRequest $request
     * @return string
     */
    public function lookup(LazyRequest $request)
    {
        $file = $request->input('url');
        $type = $request->input('type', 0);
        if($type){//搜索查看
            $file = STORAGE_PATH.DS.'files'.DS.$file;
        }
        $content = LazyFile::getFile($file);
        return $this->ajaxReturn(true, $content);
    }

    /**
     * 保存已编辑文档
     * @param LazyRequest $request
     * @return string
     */
    public function eidt(LazyRequest $request)
    {
        $url = $request->input('url');
        $content = $request->input('data');
        $status = LazyFile::setFile($url, $content);
        return $this->ajaxReturn(false === $status ? 0 : 1);
    }
    
}