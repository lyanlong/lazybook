<?php
/**
 * Created by lazy make:model.
 */

namespace App\Models;


class FTools
{
    protected $toollist = [
        [
            'id' => 1,
            'name' => 'phpserial转换器',
            'url'  => '/admin/phpserial'
        ],
        [
            'id' => 2,
            'name' => 'sdk api地址调试',
            'url'   => '/admin/sdk'
        ],
        [
            'id' => 3,
            'name' => 'run code',
            'url'   =>  '/admin/runcode'
        ]
    ];
    
    public function getTools()
    {
        return $this->toollist;
    }
}