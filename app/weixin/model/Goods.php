<?php

namespace app\weixin\model;

use traits\model\SoftDelete;

class Goods extends Common{

    use SoftDelete;

    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'goods_delete_time';

    public function getGoodsList(){
        return self::all(function($query){
            $query->where('status',1);
        });
    }
}