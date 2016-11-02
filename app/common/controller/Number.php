<?php
namespace app\common\controller;

class Number
{

    //创建订单类型
    const CREATE_ORDER_TYPE = 1;

    /**
     * 生成订单号
     */
    public static function createOrderSn(){
        return self::CREATE_ORDER_TYPE . date('ymdHis') . rand(10000,99999);
    }
}
