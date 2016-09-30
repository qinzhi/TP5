<?php
namespace app\weixin\controller;

use think\Controller;

class Order extends Controller
{
    public function create(){
        return $this->fetch();
    }
}
