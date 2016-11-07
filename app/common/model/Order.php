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

    public function getOrderList($params = [],$offset = 0,$limit = 10){
        if(!empty($params['member_id'])){
            $this->where('t.member_id',$params['member_id']);
        }
        if(isset($params['status'])){
            $this->where('t.status',$params['status']);
        }
        if(isset($params['evaluation_status'])){
            $this->where('t.evaluation_status',$params['evaluation_status']);
        }
        $orders = $this->alias('t')->field('t.*,t1.*,t2.*,t3.cover_image,t3.unit')
                        ->join(OrderProduct::TABLE_NAME . ' as t1','t.order_sn=t1.order_sn','left')
                        ->join(OrderAddress::TABLE_NAME . ' as t2','t.order_sn=t2.order_sn','left')
                        ->join(Goods::TABLE_NAME . ' as t3','t1.goods_id=t3.id','left')
                        ->limit($offset,$limit)->select();
        $orderList = [];
        foreach ($orders as $order){
            if(!isset($orderList[$order['order_sn']])){
                $orderList[$order['order_sn']] = [
                    'order_sn' => $order['order_sn'],
                    'pay_sn' => $order['pay_sn'],
                    'pay_type' => $order['pay_type'],
                    'pay_time' => $order['pay_time'],
                    'member_id' => $order['member_id'],
                    'pay_price' => $order['pay_price'],
                    'goods_num' => 0,
                    'goods_amount' => $order['goods_amount'],
                    'freight' => $order['freight'],
                    'status' => $order['status'],
                    'status_text' => $order->status_text,
                    'send_status' => $order['send_status'],
                    'receive_status' => $order['receive_status'],
                    'evaluation_status' => $order['evaluation_status'],
                    'consignee' => $order['consignee'],
                    'mobile' => $order['mobile'],
                    'area_info' => $order['area_info'],
                    'add_time' => date('Y/m/d H:i',$order['add_time']),
                ];
            }
            $orderList[$order['order_sn']]['goods_num'] += $order['product_buy_num']; //订单商品数量
            $spec_str = $spec_key_str = '';
            if(!empty($order['product_spec_array'])){
                $spec_arr = json_decode($order['product_spec_array'],true);
                foreach ($spec_arr as $val){
                    $spec_str .= $val['value'] . '/';
                    $spec_key_str .= $val['name'] . ':' . $val['value'] . '/';
                }
            }
            $orderList[$order['order_sn']]['goods_list'][] = [
                'name' => $order['goods_name'],
                'spec_array' => $order['product_spec_array'],
                'spec_str' => trim($spec_str,'/'),
                'spec_key_str' => trim($spec_key_str,'/'),
                'buy_num' => $order['product_buy_num'],
                'sell_price' => $order['product_sell_price'],
                'cost_price' => $order['product_cost_price'],
                'freight' => $order['product_freight'],
                'cover_image' => get_img($order['cover_image']),
                'unit' => $order['unit'],
            ];
        }
        return $orderList;
    }

    public function getOrderStatusCount(){
        $count = $this->field('count(*) as count,
                            count(status=0) as no_pay_count,
                            count(status=1 or status=2 or null) as pay_count,
                            count(status=3 or null) as receive_count,
                            count(status=5 or null) as evaluation_count')->find();
        return $count;
    }
}