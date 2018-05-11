<?php
/**
 * 小工具类 > 公司sdk回调模拟
 * Created by lazy make:controller.
 */

namespace App\Controllers\Admin;


use App\Models\FSdk;
use Bootstrap\Core\LazyLog;
use Bootstrap\Core\LazyRequest;

class SdkController extends CommonController
{
    public function index()
    {
        $data = (new FSdk())->init(['domain', 'apiType', 'pathType']);
        return $this->view('admin.sdk.index', ['data' => $data]);
    }

    public function remoteHttp(LazyRequest $request)
    {
        $url = $request->input('remoteurl');
        $method = $request->input('method');
        $contentType = $request->input('contentType');
        $data = $request->input('callbackData');
        $res = $this->curlRemote($url, $method, $contentType, $data);
        if (is_null(json_decode($res))) {//非json格式直接返回
            return $res;
        } else {
            return json_encode(json_decode($res), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }


    public function curlRemote($url, $method, $contentType, $data)
    {
//        LazyLog::log('curl.log', '_request_: ' . json_encode(func_get_args()));
        $isGet = strtoupper($method) == 'GET';
        $url = $isGet ? $url. '?' . http_build_query(is_array($data) ? $data : json_decode($data)) : $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/' . $contentType . ';charset=utf-8'));

        if (!$isGet) {
            curl_setopt($ch, CURLOPT_POST, 1);
            if ($contentType == 'json') { //发送JSON数据
                $jsonstr = is_string($data) ? $data : json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonstr);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length:' . strlen($jsonstr)));
            }else{
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(is_array($data) ? $data : json_decode($data)));
            }
        }

        $res = curl_exec($ch);
        curl_close($ch);

//        LazyLog::log('curl.log', '_debug_: ' . $url);
//        LazyLog::log('curl.log', '_response_: ' . $res);
        return $res;
    }
}