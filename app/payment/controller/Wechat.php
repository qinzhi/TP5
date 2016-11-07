<?php
/**
 * 微信支付
 */
namespace app\payment\controller;

use app\common\model\Order;
use app\payment\service\WxPay;
use app\weixin\controller\Weixin;
use think\Controller;
use think\Cookie;
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
            if(empty($openid)){
                $openid = Cookie::get('openid');
            }

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
            Log::record('微信异步通知：验证成功');

            $order_sn= $result['out_trade_no'];//商户系统的订单号
            Log::record('订单号：' . $order_sn);
            $total_fee = $result['total_fee'];//订单总金额，单位为分

            call_user_func_array([$this,'replyNotify'],[$wxPayService,['return_code' => 'SUCCESS','return_msg' => 'OK']]);
        }else{
            call_user_func_array([$this,'replyNotify'],[$wxPayService,['return_code' => 'FAIL','return_msg' => '签名错误']]);
        }
    }

    private function replyNotify($wxPayService,array $replay){
        echo $wxPayService->ToXml($replay);
    }

}