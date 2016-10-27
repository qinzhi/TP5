<?php
/**
 * 微信支付
 */
namespace app\payment\controller;

use app\common\model\Order;
use app\common\service\WxPay;
use app\weixin\controller\Weixin;
use think\Controller;
use think\Log;
use think\Request;


class Wechat extends Controller {

    public function __construct()
    {
        parent::__construct();
        \think\Config::set('session.prefix','wx_');//session前缀
        \think\Config::set('cookie.prefix','wx_');//cookie前缀
    }

    /**
     * 金额数字长度，金额单位为分。
     */
    const MONEY_LENGTH	= 8;

    public function index(){
        $ordersn = Request::instance()->get('ordersn');
        $order = Order::getByOrderSn($ordersn);
        if(!empty($order) && $order['status'] == 0){
            $this->assign('order',$order);

            $openid = Request::instance()->request('openid');

            $wxPayService = new WxPay();
            if(empty($openid)){
                $openid = $wxPayService->getOpenid();
            }
            $notifyUrl = url('wechat/notify','',true,true);

            $json = $wxPayService->createJsapiOrder($ordersn, $openid, $order['pay_price'], '订单支付',$notifyUrl);

            $returnUrl = url('/weixin/order/payReturn');
            $this->assign('wx_config',Weixin::getWeixinSign());
            $this->assign('returnUrl',$returnUrl);
            $this->assign('json',$json);
            $this->assign('caption','微信支付');
            return $this->fetch();
        }else{
            if(empty($order)){
                $msg = '该订单不存在';
            }elseif($order['status'] == -1){
                $msg = '该订单已取消';
            }else{
                $msg = '该订单已支付';
            }
            $this->error($msg);
        }
    }

    /**
     * 微信异步通知
     */
    public function notify(){
        $wxPayService = new WxPay();
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $result = $wxPayService->ToArray($xml);

        Log::record('微信异步通知：' . json_encode($result));

        if($result['return_code'] == 'SUCCESS' && $wxPayService->CheckSign($result) === true){
            Log::record('微信异步通知：验证成功');return;
            $orderId = $result['out_trade_no'];//商户系统的订单号
            $total_fee = $result['total_fee'];//订单总金额，单位为分

            if($attach['type'] == 'recharge'){ //充值
                $rid = $attach['rid'];

                $where = array('id'=>$rid);
                $data = array(
                    'ispay'         => 1,
                    'paytime'       => time(),
                );

                M('CompanyRechargeOrder')->where($where)->save($data);

                $total_fee /= 100;
                $where = array('id'=>$companyId);
                M('Company')->where($where)->setInc('money',$total_fee);
            }elseif($attach['type'] == 'pay'){
                // 设置不超时
                set_time_limit(0);

                $orderId = $attach['rid'];//商户系统的订单号
                $order = M('ProductCart')->where(array('orderid'=>$orderId))->find();

                if(!empty($order) && $order['paid'] == 0) {

                    $this->payHandle($order,$result['transaction_id']);//支付成功处理事件

                    $set_num = M()->table(array('pigcms_product_cart'=>'c','pigcms_product_cart_list'=>'l'))
                        ->field('l.total,l.productid,l.commision_rate,l.price,l.id')
                        ->where('c.orderid = '.$orderId.'  AND l.cartid = c.id')
                        ->select();
                    if($set_num){
                        foreach($set_num as $k=>$v){
                            if(floatval($v['commision_rate']) > 0){//佣金结算单个
                                $commision = round(floatval($v['commision_rate']) * floatval($v['price']) * floatval($v['total']),2);
                                $data = array(
                                    'commision' => $commision
                                );
                                $where = array(
                                    'id' => $v['id']
                                );
                                M('product_cart_list')->where($where)->save($data);
                            }
                            M("Product")->where(array('id' => $v['productid']))->setInc('salecount', $v['total']);
                        }
                    }

                    //佣金
                    if(floatval($order['commision_rate']) > 0){
                        $commision = round(floatval(floatval($order['surplus']) + floatval($order['money_paid'])) * floatval($order['commision_rate']),2);
                        $data = array(
                            'id' => $order['id'],
                            'commision' => $commision
                        );
                        M('ProductCart')->save($data);
                    }

                    /* end -*/

                    $this->orderPaidSms($order);

                    //调取分配订单方法
                    $this->autoAllotByOrderId($order);

                    //新版商城加积分

                    //is_present 大于 0，则说明总公司是送积分的，并且是开启积分的

                    if($order['is_present'] >0)
                    {
                        $userinfo_db=M('Userinfo');

                        $thisUser = $userinfo_db->where(array('id'=>$order['uid']))->find();

                        $userArr = array(
                            'id' => $order['uid'],
                            'total_score' => $thisUser['total_score']+round($order['money_paid'])
                        );

                        $userinfo_db->save($userArr);

                        $pay_point = round($order['money_paid']);

                        $score_log=array(
                            'member_id'=>$order['uid'],
                            'wecha_id'=>$order['wecha_id'],
                            'pay_point'=>$pay_point,
                            'change_time'=>time(),
                            'change_type'=>4,
                            'change_desc'=>'购买商品送积分',
                            'token'=>$order['token'],
                            'tp'=>1
                        );

                        M("Userinfo_account_log")->add($score_log);

                    }

                }
            }
            call_user_func_array([$this,'replyNotify'],[$wxPayService,['return_code' => 'SUCCESS','return_msg' => 'OK']]);
        }else{
            call_user_func_array([$this,'replyNotify'],[$wxPayService,['return_code' => 'FAIL','return_msg' => '签名错误']]);
        }
    }

    private function replyNotify($wxPayService,array $replay){
        echo $wxPayService->ToXml($replay);
    }

}