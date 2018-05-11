<?php
/**
 * 文档节点控制器
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use App\Models\Menus;
use Bootstrap\Core\LazyFile;
use Bootstrap\Core\LazyRequest;

class NodeController extends CommonController
{

    /**
     * 预览指定节点下所有文档
     * @param LazyRequest $request
     * @return string
     */
    public function preview(LazyRequest $request)
    {
        $path = $request->input('url');
        $path = STORAGE_PATH.DS.'files'.DS.$path;
        $file = [];
        LazyFile::searchDir($path, $file, IS_WIN);
        return $this->ajaxReturn(true, $file);
    }
    
    
    /**
     * 点击添加文档或目录
     * @param LazyRequest $request
     * @return string
     */
    public function addNode(LazyRequest $request)
    {
        $pid = $request->input('pid');
        $name = $request->input('value');
        $type = $request->input('type');
        if($type){//添加目录
            $status = (new Menus()) -> addChildMenu($pid, $name);
        }else{//添加文档
            $status = LazyFile::setFile(STORAGE_PATH.DS.'files'.DS.$pid.DS.$name);
        }
        
        return $this->ajaxReturn($status);
    }

    /**
     * 上传文档
     * @param LazyRequest $request
     * @return string
     */
    public function syncUpload(LazyRequest $request)
    {
        $url = $request->input('url');
        $status = LazyFile::uploadFile(STORAGE_PATH.DS.'files'.DS.$url, $_FILES['upload_article']);
        if(!$status){
            return $this->ajaxReturn(false, '', ['error' => '上传失败']);
        }
        return $this->ajaxReturn(true, '', ['msg' => '上传成功']);;
    }

    /**
     * 删除文档[可批量操作]
     * @param LazyRequest $request
     * @return string
     */
    public function del(LazyRequest $request)
    {
        $url = $request->input('url');
        $dellist = $request->input('dellist');
        $status = LazyFile::delFile(STORAGE_PATH.DS.'files'.DS.$url, $dellist);
        return $this->ajaxReturn(false === $status ? 0 : 1);
    }
}