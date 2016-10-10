<?php
/**
 * 微信支付
 */
namespace app\payment\controller;

use Common\Library\Org\Util\Validate;
use Think\Controller;
use think\Request;

class WxPay extends Controller {

    /**
     * 金额数字长度，金额单位为分。
     */
    const MONEY_LENGTH	= 8;

    public function index(){

        $order_id = Request::instance()->request('orderId');
        if(empty($order_id)){
            throw new \Exception("订单编号不能为空");
        };

        $token = I('request.token');
        $attach = array();
        $attach['type'] = I('request.type');
        $attach['token'] = $token;
        $attach['companyId'] = I('request.companyId/d');
        $attach['rid'] = $order_id;//订单编号

        if($attach['type'] == 'recharge'){

            $price = I('request.price/f');
            if($price <= 0){
                throw new \Exception("金额必须大于0");
            }

            $order_id = 'CZ' . $order_id;
            $returnUrl = U('/Merchant/Store/center');
            $description = '充值';

            $this->assign('getSign',U("/Merchant/Home/getWeixinSign"));
        }elseif($attach['type'] == 'pay'){
            $order = M('ProductCart')->where(array(
                'token' => $token,
                'cid' => $attach['companyId'],
                'orderid' => $order_id
            ))->find();
            if(empty($order)){
                throw new \Exception("订单异常");
            }else{
                $returnUrl = U('/Mall/Store/payReturn',array('token'=>$token,'orderid'=>$order_id));
                if($order['paid']){
                    $this->redirect($returnUrl);
                }
                $price = $order['money_paid'];
            }

            $description = '订单支付';

            $this->assign('title','微信支付');
            $this->assign('getSign',U("/Mall/Home/getWeixinSign"));
        }


        $tradeNumber = $this->createTradeNumber($order_id,$price);

        $openid = I('request.openId','','trim');

        $wxPayService = D('Common/WxPay','Service')->initConfig($token);
        if(empty($openid)){
            $openid = $wxPayService->getOpenid();
        }

        $notifyUrl = getSiteUrl() . U('WxPay/notify');

        $json = $wxPayService->createJsapiOrder($tradeNumber, $openid, $price, $description,$notifyUrl, http_build_query($attach));

        $this->assign('returnUrl',$returnUrl);
        $this->assign('json',$json);
        $this->assign('money',$price);
        $this->assign('caption','微信支付');
        $this->display();
    }

    public function notify(){
        $wxPayService = D('Common/WxPay','Service');
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $result = $wxPayService->ToArray($xml);
        parse_str($result['attach'],$attach);//附加参数
        $token = $attach['token'];
        $companyId = $attach['companyId'];

        $wxPayService->initConfig($token);
        if($result['return_code'] == 'SUCCESS' && $wxPayService->CheckSign($result) === true){

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
                            'user_id'=>$order['uid'],
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

            $this->replyNotify($wxPayService,array(
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
            ));
        }else{
            $this->replyNotify($wxPayService,array(
                'return_code' => 'FAIL',
                'return_msg' => '签名错误'
            ));
        }
    }

    public function payHandle($thisOrder,$transaction_id){
        $token = $thisOrder['token'];
        $wecha_id = $thisOrder['wecha_id'];
        $member_card_create_db=M('Member_card_create');
        $userCard=$member_card_create_db->where(array('token'=>$token,'wecha_id'=>$wecha_id))->find();
        $userinfo_db=M('Userinfo');
        if ($userCard){
            $member_card_set_db=M('Member_card_set');
            $thisCard=$member_card_set_db->where(array('id'=>intval($userCard['cardid'])))->find();
            if ($thisCard){
                $set_exchange = M('Member_card_exchange')->where(array('cardid'=>intval($thisCard['id'])))->find();
                //
                $arr['token']=$token;
                $arr['wecha_id']=$wecha_id;
                $arr['expense']=$thisOrder['price'];
                $arr['time']=time();
                $arr['cat']=99;
                $arr['staffid']=0;
                $arr['score']=intval($set_exchange['reward'])*$arr['expense'];

                if(isset($_GET['redirect'])){
                    $infoArr = explode('|',$_GET['redirect']);

                    $param = explode(',',$infoArr[1]);
                    if($param){
                        foreach ($param as $pa){
                            $pas=explode(':',$pa);
                            if($pas[0] == 'itemid'){
                                $arr['itemid']=$pas[1];
                            }
                        }
                    }

                }

                M('Member_card_use_record')->add($arr);

                $thisUser = $userinfo_db->where(array('token'=>$thisCard['token'],'wecha_id'=>$arr['wecha_id']))->find();
                $userArr=array();
                $userArr['total_score']=$thisUser['total_score']+$arr['score'];
                $userArr['expensetotal']=$thisUser['expensetotal']+$arr['expense'];
                $userinfo_db->where(array('token'=>$token,'wecha_id'=>$arr['wecha_id']))->save($userArr);
            }
        }
        $data_order['id'] = $thisOrder['id'];
        $data_order['paid'] = 1;

        $order_model= M('ProductCart');
        $data_order['paytype'] = 'weixin';
        $data_order['third_id'] = $transaction_id;
        $data_order['transactionid'] = $transaction_id;
        $data_order['buytime'] = time();
        $data_order['is_read'] = 0;

        $order_model->save($data_order);

        if($_GET['pl']){
            $database_platform_pay = D('Platform_pay');
            $data_platform_pay['orderid'] = $thisOrder['orderid'];
            $data_platform_pay['price'] = $thisOrder['price'];
            $data_platform_pay['wecha_id'] = $thisOrder['wecha_id'];
            $data_platform_pay['token'] = $thisOrder['token'];
            //$data_platform_pay['from'] = $this->from;
            $data_platform_pay['time'] = $_SERVER['REQUEST_TIME'];
            $database_platform_pay->data($data_platform_pay)->add();
        }

        return $thisOrder;
    }

    //发送短信
    private function orderPaidSms($order) {
        if (empty($order)) return;

        //查找公司信息
        $company = M('company')->find($order['cid']);
        if (empty($company)) return;

        //导入短信
        $smsService = D('Common/Sms','Service');

        // 御宝羊奶商城不提醒提货，只提醒安排发货通知！
        if ($order['delivery'] == 1 && $order['token'] != 'efjdyb1396147388') {
            //如果是快递方式是“上门取货”，
            $content = "尊敬的客户，请您去{$company['address']}提货,门店电话:{$company['mp']},提货码{$order['validate_pwd']}，回复TD退订";
        } else {
            //其他快递方式，
            $msg = '';
            if(!empty($order['validate_pwd'])){
                $msg = '提货码：'.$order['validate_pwd'].',';
            }
            $content = "尊敬的客户，您的订单已经提交成功,我们会第一时间为您安排发货，{$msg}回复TD退订";
        }

        if(Validate::mobi($order['tel']) === true){
            $smsService->verificationSmsSend($order['tel'],$content);
        }
        //短信通知商家
        if($company['mp']!=''){
            $tel = $company['mp'];
        }else{
            $tel = $company['mp1'];
        }
        if ($tel) $smsService->verificationSmsSend($tel,"您好，会员{$order['truename']}刚刚对订单号：{$order['orderid']}的订单进行了支付，请及时处理，回复TD退订");
    }

    private  function autoAllotByOrderId($order)
    {
        //根据订单号反查 总公司id

        $goodsOrderModel = new \Common\Model\ProductOrderModel();
        //查询为类型总公司的订单
        $orderInfo=$goodsOrderModel->getOrderInfoByOrderId($order['orderid']);
        if(!empty($orderInfo))
        {
            //首先查询总商城是否开启
            $storeSetting 	= new \Common\Model\StoreSettingsModel();
            $storeInfo 		= $storeSetting->getStoreSettings($orderInfo['cid']);
            //首先查询门店是否开启配送点
            $companyModel	= new \Common\Model\CompanyModel();
            //获取总商城下对应的所有门店
            $allStoreInfo 	= $companyModel->listAllStore($orderInfo['token']);
            //过滤掉所有未开启的门店信息

            $stores=array();

            foreach ($allStoreInfo as $key=> $store)
            {
                if($store['is_dilivery']== 1 && $store['addr_keyword'])
                {
                    $stores[]=$allStoreInfo[$key];
                }
            }
            if($storeInfo['head_store_open'] && $storeInfo['order_allot'])
            {
                $keyWordCount=array();
                $fullAddress=$companyModel->getFullAddress($orderInfo);

                foreach($stores as $data)
                {
                    $count=0;
                    $allotAddress   =explode(";",$data['addr_keyword']);

                    foreach($allotAddress as $address)
                    {
                        if(strpos($fullAddress,$address) !== false)
                        {

                            $count++;

                        }
                    }

                    $keyWordCount[$data['id']]+=$count;

                }
                //此处循环了一个订单的最佳匹配
                if(count($keyWordCount)>=1)
                {
                    arsort($keyWordCount);
                    $allotCompanyId=key($keyWordCount);

                    if($keyWordCount[$allotCompanyId]>0 && !empty($orderInfo['id']))//此处找到了最佳匹配,将订单分配给该门店
                    {
                        $goodsOrderModel->updateAllotOrder($orderInfo['id'],$allotCompanyId);
                    }
                }
                //}
            }
        }
    }

    private function replyNotify($wxPayService,array $replay){
        echo $wxPayService->ToXml($replay);
    }

    /**
     * 创建交易号。
     * 由于订单价格有时需要修改，如果交易号不变而金额变化了是不能重复提交付款的（因为微信已经生成了预付订单，交易号和金额不能再变化！），所以订单号带上金额就可以解决修改价格的问题。
     */
    public static function createTradeNumber($orderNumber, $money, $moneyLength = self::MONEY_LENGTH)
    {
        return $orderNumber.str_pad($money * 100, $moneyLength, '0', STR_PAD_LEFT);
    }


}