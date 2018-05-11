<?php
/**
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use App\Models\Booklogs;
use Bootstrap\Core\LazyRequest;

class SearchController extends CommonController
{
    /**
     * 搜索词典库
     * @param LazyRequest $request
     * @return string
     */
    public function search(LazyRequest $request)
    {
        $key = $request->input('key');
        $res = (new Booklogs())->getBooklog(['id','url'], ["url LIKE '%{$key}%'"]);

        $data['items'] =   array_map(function($item){
            return [
                'id' => $item['id'],
                'text' => ltrim($item['url'], '/'),
            ];
        }, $res);
        $data['total_count'] = count($res);
        return $this->ajaxReturn(true, '', $data);
    }

    /**
     * 更新词典库
     * @return string
     */
    public function updateDic()
    {
        $status = (new Booklogs())->syncBooklog();
        return $this->ajaxReturn($status);
    }
}