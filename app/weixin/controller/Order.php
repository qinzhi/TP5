<?php
namespace app\weixin\controller;

use app\common\controller\Number;
use app\common\model\Address;
use app\common\model\Cart;
use think\Controller;
use think\Cookie;
use think\Db;

class Order extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->member_id = 1;
    }

    public function create(){
        if(Cookie::has('cart_id')){
            $cartModel = new Cart();
            $cart_id = Cookie::get('cart_id');
            $cart_id = json_decode($cart_id,true);
            $products = $cartModel->getList($this->member_id,$cart_id);
            $this->assign('products',$products);

            $addressModel = new Address($this->member_id);
            $address = $addressModel->getDefault();
            $this->assign('address',!empty($address->member_id)?$address->member_id:'');
            return $this->fetch();
        }else{
            $this->error('没有选中购物车商品');
        }
    }

    public function add($address_id,$note){
        if(Cookie::has('cart_id')){
            $cartModel = new Cart();
            $cart_id = Cookie::get('cart_id');
            $cart_id = json_decode($cart_id,true);
            $ordersn = Number::createOrderSn();//订单号
            $products = $cartModel->getList($this->member_id,$cart_id);
            $pay_price = $freight = $goods_amount = 0;
            foreach ($products as & $product){
                $pay_price += $product['sell_price'];
                $goods_amount += $product['sell_price'];
                $arr_product[] = [
                    'order_sn' => $ordersn,
                    'product_id' => $product['product_id'],
                    'goods_id' => $product['goods_id'],
                    'goods_name' => $product['name'],
                    'spec_array' => $product['spec_array'],
                    'buy_num' => $product['cart_num'],
                    'sell_price' => $product['sell_price'],
                    'cost_price' => $product['cost_price'],
                ];
            }
            $arr_order = [
                'member_id' => $this->member_id,
                'order_sn' => $ordersn,
                'pay_type' => 1,    //微信支付
                'pay_price' => $pay_price,
                'goods_amount' => $goods_amount,
                'freight' => $freight,
                'note' => $note,
                'add_time' => time(),
            ];
            $addressModel = new Address($this->member_id);
            $address = $addressModel->getAddressById($address_id)->member_id;
            if(!empty($address)){
                $address = $addressModel->getDefault()->member_id;
                if(empty($address)){
                    return ['code' => 0,'msg' => '收货地址不能为空'];
                }
            }
            $arr_address = [
                'order_sn' => $ordersn,
                'consignee' => $address['consignee'],
                'mobile' => $address['mobile'],
                'province_id' => $address['province_id'],
                'city_id' => $address['city_id'],
                'county_id' => $address['county_id'],
                'address' => $address['address'],
                'area_info' => $address['area_info'],
            ];
            // 启动事务
            Db::startTrans();
            try{
                $order_status = Db::name('order')->insert($arr_order);
                $address_status = Db::name('order_address')->insert($arr_address);
                $product_status = Db::name('order_product')->insertAll($arr_product);
                if($order_status && $address_status && $product_status){
                    // 提交事务
                    Db::commit();
                    //清空购物车
                    Cookie::delete('cart_id');
                    $cartModel->deleteByIds($cart_id);

                    return ['code' => 1,'msg' => '订单创建成功','ordersn',$ordersn];
                }
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
            }
        }else{
            return ['code' => 0,'msg' => '没有支付商品'];
        }
    }
}
