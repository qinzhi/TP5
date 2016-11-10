<?php

namespace app\admin\controller;

use app\common\model\Order as OrderModel;

class Order extends Admin {

    public function index(){
        $orderModel = new OrderModel();
        $orderList = $orderModel->getOrderList();fb($orderList);
        $this->assign('orderList',$orderList);
        return $this->fetch();
    }

    public function detail($order_sn){
        $orderModel = new OrderModel();
        $orderList = $orderModel->getOrderByOrderSn($order_sn);fb($orderList);
        if(!empty($orderList)){
            $this->assign('order',current($orderList));
            $this->assign('orderList',$orderList);
            return $this->fetch();
        }else{
            $this->error('该订单不存在,订单号：' . $order_sn);
        }
    }

}