<?php
namespace app\weixin\controller;

use app\common\model\Address;
use app\common\model\Cart;
use think\Controller;

class Order extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->member_id = 1;
    }

    public function create(){
        if(!empty($_COOKIE['cart_id'])){
            $cartModel = new Cart();
            $cart_id = json_decode($_COOKIE['cart_id'],true);
            $products = $cartModel->getList($this->member_id,$cart_id);
            $this->assign('products',$products);

            $addressModel = new Address($this->member_id);
            $address = $addressModel->getDefault();
            $this->assign('address',!empty($address->member_id)?$address->member_id:'');
            return $this->fetch();
        }else{

        }
    }

    public function add(){

    }
}
