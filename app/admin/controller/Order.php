<?php

namespace app\admin\controller;

class Order extends Admin {

    public function index(){
        return $this->fetch();
    }

    public function detail($order_sn){
        return $this->fetch();
    }

}