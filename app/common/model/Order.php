<?php

namespace app\common\model;

use think\Model;


class Order extends Model
{
    const TABLE_NAME = 'order';

    public function getStatusTextAttr($value,$data)
    {
        $status = [-1=>'已取消',0=>'未支付',1=>'已支付',2=>'部分发货',3=>'已发货',4=>'部分收货',5=>'已收货'];
        return $status[$data['status']];
    }

    public function getSourceTextAttr($value,$data){
        $source = [1=>'微信商城'];
        return $source[$data['source']];
    }

    public function getPayTypeTextAttr($value,$data){
        $payType = [1=>'微信支付'];
        return $payType[$data['pay_type']];
    }

    /**
     * 根据订单号获取订单
     * @param $order_sn
     */
    public function getOrderByOrderSn($order_sn){
        $orders = $this->alias('t')
                        ->join(OrderAddress::TABLE_NAME . ' as t1','t.order_sn=t1.order_sn','left')
                            ->join(OrderProduct::TABLE_NAME . ' as t2','t.order_sn=t2.order_sn','left')
                                ->where('t.order_sn',$order_sn)->select();
        return $orders;
    }
}