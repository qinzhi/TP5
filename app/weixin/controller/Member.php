<?php

namespace app\weixin\controller;



use app\common\model\Order;

class Member extends Weixin  {

    public function index(){

        //订单各个状态数量
        $orderModel = new Order();
        $count = $orderModel->getOrderStatusCount();
        $this->assign('count',$count);

        $this->assign('cartNum',$this->getCartNum()); //购物车商品数量

        $this->assign('nav_type',4);
        return $this->fetch();
    }

    public function person(){
        return $this->fetch();
    }
}