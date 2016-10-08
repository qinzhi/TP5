<?php
namespace app\weixin\controller;

use app\common\Model\Cart;
use think\Controller;

class Order extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->user_id = 1;
    }

    public function create(){
        if(!empty($_COOKIE['cart_id'])){
            $cartModel = new Cart();
            $cart_id = json_decode($_COOKIE['cart_id'],true);
            $products = $cartModel->getList($this->user_id,$cart_id);
            $this->assign('products',$products);
            return $this->fetch();
        }else{

        }

    }
}
