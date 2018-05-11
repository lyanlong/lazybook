<?php
/**
 * Created by lazy make:model.
 */

namespace App\Models;


class FSdk
{
    protected $domain = [//服务器主机IP地址
        //正式服
        '120.76.29.90' => '蜀山[主]',
        '120.76.31.53' => '蜀山[备]',
        '120.25.58.215' => '剑雨[主]',   //jycenter.shyouai.com
        '120.77.224.29' => '剑雨[备]',
        '115.28.104.227' => '仙魔变[主]',
        '121.42.169.188' => '仙魔变[备]',
        '47.104.187.73' => '仙魔变自发[主]',     //xmbzfcenter.shyouai.com
        '120.76.52.202' => '仙魔变自发[备]',
        '120.76.245.112' => '国战[主]',
        '120.76.244.250' => '国战[备]',
        '118.31.244.115' => '武当[主]',
        '118.31.244.110' => '武当[备]',
        '119.23.155.162' => '3D封神[主]', //fengshencenter.shyouai.com
        '119.23.155.114' => '3D封神[备]',

        '120.55.80.91' => '灵域',
        '139.196.198.126' => '幻灵',
        '61.219.16.68' => '剑雨繁体',     //lingyu-center.idplaygame.com.tw
        '139.196.198.126' => '百炼成神', //blcs.center.shyouai.com
        'muzfcenter.hnputihd.com' => '拇指自发',

        //测试服
        '121.201.1.110' => '老测[外]',
        '192.168.16.235' => '老测[内]',
        '192.168.16.234:9021' => '剑雨[内]',
        '192.168.16.233:4001' => '花木兰[内]',

        //本地
        '127.0.0.1' => 'local',
    ];

    protected $apiType = [ //接口类型
        'check_session' => 'sdk登录',    //中央服sdk登录
        'pay_callback' => 'sdk支付回调',     //中央服sdk支付回调接口
        'api' => '后台api接口',              //中央服后台api接口
    ];

    protected $pathType = [//接口文件目录结构
        '/hhsy/api/' => 'sdk api',
        '/hhsy/center/' => 'center api',
    ];


    public function init(array $fields)
    {
        $result = [];
        foreach ($fields as $filed){
            if($this->$filed){
                ksort($this->$filed);
                $result[$filed] = $this->$filed;
            }
        }
        return $result;
    }

}