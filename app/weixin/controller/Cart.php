<?php
namespace app\weixin\controller;

use app\common\model\Products;
use app\common\model\Cart as CartModel;
use think\Controller;
use think\Db;
use think\Request;

class Cart extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user_id = 1;
    }

    public function index(){
        $this->assign('totalNum',1);
        $this->assign('totalPrice',10);
        $cartModel = new CartModel();
        $products = $cartModel->getList($this->user_id);
        if(!empty($products)){
            $this->assign('products',$products);
            return $this->fetch();
        }else{
            return $this->fetch('empty');
        }
    }

    public function add($product_id,$num){
        $product = Products::get($product_id);
        if(empty($product)){
            return json(['code'=>-1,'msg'=>'商品不存在']);
        }
        $cart = Db::name('cart')->where('user_id',$this->user_id)->where('product_id',$product_id)->find();
        if($product['store_nums'] <= 0 || ($cart && ($cart['cart_num'] + $num) > $product['store_nums'])){
            $stock = $product['store_nums'] - $cart['cart_num'];
            return json(['code'=>-1,'msg'=>"库存不足，最多购买{$stock}件"]);
        }
        if(empty($cart)){
            $result = Db::name('cart')->insert([
                'product_id' => $product_id,
                'user_id' => $this->user_id,
                'cart_num' => $num,
            ]);
        }else{
            $result = Db::name('cart')->where('user_id',$this->user_id)->where('product_id',$product_id)->setInc('cart_num',$num);
        }
        if($result){
            return json(['code'=>1,'msg'=>'商品已添加至购物车']);
        }else{
            return json(['code'=>-1,'msg'=>'添加失败']);
        }
    }

    public function update($product_id,$num){
        $product = Products::get($product_id);
        if(empty($product)){
            return json(['code'=>-1,'msg'=>'商品不存在','num'=> $num]);
        }else{
            $store_nums = $product['store_nums'];
        }
        $cart = Db::name('cart')->where('user_id',$this->user_id)->where('product_id',$product_id)->find();
        if($store_nums <= 0 || ($cart && $num > $store_nums)){
            $num = $num > $store_nums ? ($store_nums > 0 ? $store_nums : 1) : $num;
            Db::name('cart')->where('user_id',$this->user_id)->where('product_id',$product_id)->setField('cart_num',$num);
            return json(['code'=>-2,'msg'=>"库存不足，最多购买{$store_nums}件",'num' => $num]);
        }
        if(!empty($cart)){
            $result = Db::name('cart')->where('user_id',$this->user_id)->where('product_id',$product_id)->setField('cart_num',$num);
        }
        if(isset($result) && $result){
            return json(['code'=>1,'msg'=>'商品已添加至购物车','num'=> $num]);
        }else{
            return json(['code'=>-1,'msg'=>'添加失败','num'=> $num]);
        }
    }
}