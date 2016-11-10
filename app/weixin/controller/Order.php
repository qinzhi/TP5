<?php
namespace app\weixin\controller;

use app\common\controller\Number;
use app\common\model\Address;
use app\common\model\Cart;
use app\payment\service\WxPay;
use app\common\model\Order as OrderModel;
use think\Cookie;
use think\Db;
use think\Log;
use think\Request;

class Order extends Weixin
{

    public $member_id;

    public $limit = 10;

    public function __construct()
    {
        parent::__construct();
        $this->member_id = $this->member['id'];
    }

    public function index(){

        $orderModel = new OrderModel();
        $count = $orderModel->getOrderStatusCount($this->member_id);
        $this->assign('count',$count);

        $status = Request::instance()->request('status/d',0);
        switch ($status){
            case 0:{//全部订单
                $title = "全部订单({$count['count']})";
                break;
            }
            case 1:{//待支付
                $title = "待支付({$count['no_pay_count']})";
                break;
            }
            case 2:{//待发货
                $title = "待发货({$count['pay_count']})";
                break;
            }
            case 3:{//待收货
                $title = "待收货({$count['receive_count']})";
                break;
            }
            case 4:{//待评价
                $title = "待评价({$count['evaluation_count']})";
                break;
            }
        }
        $this->assign('title',$title);
        $this->assign('limit',$this->limit);
        return $this->fetch();
    }

    public function getOrderList(){
        $orderModel = new OrderModel();
        $params['member_id'] = $this->member_id;
        $status = Request::instance()->request('status/d',0);
        switch ($status){
            case 0:{break;}//全部订单
            case 1:{//待支付
                $params['status'] = 0;
                break;
            }
            case 2:{//待发货
                $params['status'] = 1;
                break;
            }
            case 3:{//待收货
                $params['status'] = 3;
                break;
            }
            case 4:{//待评价
                $params['status'] = 5;
                $params['evaluation_status'] = 0;
                break;
            }
        }
        $orderList = $orderModel->getOrderList($params);
        $result['orderList'] = $orderList;
        return $result;
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
                    'product_spec_array' => $product['spec_array'],
                    'product_buy_num' => $product['cart_num'],
                    'product_sell_price' => $product['sell_price'],
                    'product_cost_price' => $product['cost_price'],
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
            if(empty($address)){
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

                    $params['ordersn'] = $ordersn;
                    $params['openid'] = $this->openid;
                    return ['code' => 1,'msg' => '订单创建成功','url'=>url('/payment/wechat/index') . '?' . http_build_query($params)];
                }else{
                    return ['code' => 0,'msg'=>'订单创建失败'];
                }
            } catch (\Exception $e) {
                Log::error('创建订单异常： ' . $e->getMessage());
                // 回滚事务
                Db::rollback();
            }
        }else{
            return ['code' => 0,'msg' => '没有支付商品'];
        }
    }

    public function test(){
        $wxPayService = new WxPay();
        $money = 1;
        $result = $wxPayService->sendRedpacket('果度千寻', $this->openid, $money,
            '账户余额提现', '祝您生活工作愉快！', '请尽快提现！');
        Log::record('@@@@@@@@@@ ' . json_encode($result));
    }
}
