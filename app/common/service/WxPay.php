<?php
/**
 * 微信支付服务接口类
 * Created by PhpStorm.
 * User: 63124
 * Date: 16-6-12
 * Time: 上午10:57
 */
namespace app\common\service;

use app\common\tools\Str;
use think\Config;
use think\Controller;
use think\Request;

$payment_path = PRODUCT_PATH . '/app/payment/library/wxpay';
require_once $payment_path . "/lib/WxPay.Api.php";
require_once $payment_path . "/example/WxPay.JsApiPay.php";
require_once $payment_path . "/example/log.php";

class WxPay{

    /**
     * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0
     * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，
     * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）
     * @var unknown_type
     */
    const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
    const CURL_PROXY_PORT = 0;//8080;

    /**
     * 统一下单接口
     */
    const API_UNIFIED_ORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * 微信红包接口
     */
    const API_REDPACKET = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';

    /*************** 交易类型 ***************/
    /**
     * 公众号支付交易类型
     */
    const TRADE_TYPE_JSAPI	= 0;

    /**
     * 扫码支付交易类型
     */
    const TRADE_TYPE_NATIVE	= 1;

    /**
     * APP支付交易类型
     */
    const TRADE_TYPE_APP	= 2;

    /**
     * 金额数字长度，金额单位为分。
     */
    const MONEY_LENGTH	= 8;

    /**
     * 从支付配置初始化失败错误类型
     */
    const ERROR_INIT_CONFIG_FAILED	= 101;

    /**
     * 从支付配置初始化失败错误消息
     */
    const MESSAGE_INIT_CONFIG_FAILED = '微信支付服务从支付配置初始化失败！';

    /**
     * 商户ID
     */
    protected $merchantId;

    /**
     * 商户密钥
     */
    protected $merchantKey;

    /**
     * 合作者密钥
     */
    protected $partnerKey;

    protected $token;

    protected $appid;

    protected $secret;

    protected $encodingAesKey;

    /**
     * SSL证书
     */
    protected $sslCert;

    /**
     * SSL密钥
     */
    protected $sslKey;

    /**
     */
    protected $rootCa;

    /**
     * 构造方法
     */
    public function __construct($token = '',$app_id = '' ,$app_secret = '',$encodingAesKey = '',$mch_id = '',$mch_key = ''){

        $this->token = !empty($token) ? $token : Config::get('weixin.token');
        $this->appid = !empty($app_id) ? $app_id : Config::get('weixin.app_id');
        $this->secret = !empty($app_secret) ? $app_secret : Config::get('weixin.app_secret');
        $this->encodingAesKey = !empty($encodingAesKey) ? $token : Config::get('weixin.encodingAesKey');

        $this->merchantId	= !empty($mch_id) ? $mch_id : Config::get('weixin.mch_id');
        $this->merchantKey	= !empty($mch_key) ? $mch_key : Config::get('weixin.mch_key');

        $this->setCert();
    }

    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     *  1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     *  2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     *
     * @return mixed 用户的openid
     */
    public function getOpenid()
    {
        $openid = Request::instance()->request('openid');
        if($openid != ''){
            return $openid;
        }

        $wechatService = new Wechat($this->token,$this->appid,$this->appsecret,$this->encodingAesKey);
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = getFullUrl();
            $url =$wechatService->getOauthRedirect($baseUrl,'STATE','snsapi_base');
            header('Location: ' . $url);
            exit;
        } else {
            //获取code码，以获取openid
            $result = $wechatService->getOauthAccessToken();


            $openid = $result['openid'];
            cookie('openid',$openid);
            return $openid;
        }
    }

    /**
     * 设置HTTPS证书和密钥
     */
    public function setCert($sslCert = '', $sslKey = '', $rootCa = '')
    {
        $this->sslCert	= !empty($sslCert) ? $sslCert : Config::get('weixin.ssl_cert_path');
        $this->sslKey	= !empty($sslKey) ? $sslKey : Config::get('weixin.ssl_key_path');
        $this->rootCa	= !empty($rootCa) ? $rootCa : Config::get('weixin.root_ca_path');
        return $this;
    }

    /**
     * 生成公众号预支付订单
     * @param $orderId int 订单ID
     * @param $openId int 微信用户ID
     * @param $money float 支付金额，单位为元
     * @param $description string 订单描述
     * @param $notifyUrl string 通知回调地址
     * @param $attach string 附加数据
     * @return array | false 成功返回JS调用所需的JSON参数，失败返回false。
     */
    public function createJsapiOrder($orderId, $openId, $money, $description, $notifyUrl,$attach = '')
    {

        $params = array(
            'appid' => $this->appid,
            'mch_id'			=> $this->merchantId,
            'nonce_str'			=> Str::getRandChar(16),
            'trade_type'		=> 'JSAPI',
            'device_info'		=> 'WEB',
            'out_trade_no'		=> $orderId,
            'openid'			=> $openId,
            'total_fee'			=> $money * 100,
            'body'				=> $description,
            'notify_url'		=> $notifyUrl,
            'spbill_create_ip'	=> $_SERVER['REMOTE_ADDR'],
            'is_subscribe'		=> 'Y'
        );
        if(!empty($attach)){
            $params['attach'] = $attach;
        }

        $xmlObject = $this->createUnifiedOrder($params);

        /*if (!$xmlObject)
        {
            $moduleLogModel = D('ModuleLog');
            $moduleLogModel->addModuleLog($this->token, $openId, $this, $params);
            return false;
        }*/

        return $this->GetJsApiParameters($xmlObject);
    }

    /**
     * 发红包
     * @param $send_name string 商户名称
     * @param $re_openid string 用户openid
     * @param $total_amount int 付款金额
     * @param $act_name string 活动名称
     * @param $wishing string 红包祝福语
     * @param $remark string 备注
     * @param $scene_id  string 场景id 【PRODUCT_1:商品促销 PRODUCT_2:抽奖 PRODUCT_3:虚拟物品兑奖  PRODUCT_4:企业内部福利 PRODUCT_5:渠道分润 PRODUCT_6:保险回馈 PRODUCT_7:彩票派奖 PRODUCT_8:税务刮奖】
     * @return mixed
     * @throws \Exception
     */
    public function sendRedpacket($send_name, $re_openid, $total_amount,$act_name, $wishing, $remark,$scene_id = ''){
        $params = array(
            'wxappid'		=> $this->appid,
            'mch_id'		=> $this->merchantId,
            'mch_billno'	=> $this->creatBillNumber(),
            'nonce_str'		=> Str::getRandChar(16),
            'send_name'		=> $send_name,
            're_openid'		=> $re_openid,
            'total_num'		=> count(explode(',', $re_openid)),
            'total_amount'	=> $total_amount * 100,
            'act_name'		=> $act_name,
            'wishing'		=> $wishing,
            'remark'		=> $remark,
            'client_ip'		=> $_SERVER['REMOTE_ADDR']
        );
        if(!empty($scene_id)) $params['scene_id'] = $scene_id;

        $params['sign'] = $this->MakeSign($params);

        $xmlData = $this->ToXml($params);
        $response = $this->postXmlCurl($xmlData, self::API_REDPACKET,true);
        $result = $this->ToArray($response);
        //$this->CheckSign($result);

        // 业务结果错误
        if ($result['return_code'] != 'SUCCESS')
        {
            throw new \Exception($result['err_code_des']);
        }

        return $result;
    }

    /**
     * 创建账单号
     */
    protected function creatBillNumber()
    {
        return $this->merchantId.date('Ymd').mt_rand(1000000000, 9999999999);
    }

    /**
     *
     * 获取jsapi支付的参数
     * @param array $UnifiedOrderResult 统一支付接口返回的数据
     * @throws \Exception
     * @return json数据，可直接填入js函数作为参数
     */
    public function GetJsApiParameters($UnifiedOrderResult)
    {
        if(!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || $UnifiedOrderResult['prepay_id'] == "")
        {
            throw new \Exception("参数错误");
        }

        $params = array(
            'appId' => $UnifiedOrderResult["appid"],
            'timeStamp' => strval(time()),
            'nonceStr' => String::getRandChar(16),
            'package' => "prepay_id=" . $UnifiedOrderResult['prepay_id'],//$UnifiedOrderResult['prepay_id'],
            'signType' => 'MD5'
        );
        $params['paySign'] = $this->MakeSign($params);

        $parameters = json_encode($params);
        return $parameters;
    }

    /**
     * 通过统一下单接口生成预支付订单
     */
    public function createUnifiedOrder(array &$params)
    {
        $params['sign'] = $this->MakeSign($params);
        $xmlData = $this->ToXml($params);
        $response = $this->postXmlCurl($xmlData, self::API_UNIFIED_ORDER);
        $result = $this->ToArray($response);
        $this->CheckSign($result);

        // 业务结果错误
        if ($result['return_code'] != 'SUCCESS')
        {
            throw new \Exception($result['err_code_des']);
        }

        return $result;
    }

    /**
     * 生成签名
     * @param $params
     * @return string   签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function MakeSign($params)
    {
        //签名步骤一：按字典序排序参数
        ksort($params);
        $string = $this->ToUrlParams($params);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->merchantKey;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams($params)
    {
        $buff = "";
        foreach ($params as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 输出xml字符
     * @throws \Exception
     **/
    public function ToXml($params)
    {
        if(!is_array($params)
            || count($params) <= 0)
        {
            throw new \Exception("数组数据异常！");
        }

        $xml = "<xml>";
        foreach ($params as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     * @throws \Exception
     */
    public function ToArray($xml)
    {
        if(!$xml){
            throw new \Exception("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 检测签名
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public function CheckSign($params)
    {
        //fix异常
        if(array_key_exists('sign', $params) === false){
            throw new \Exception("签名错误！");
        }

        $sign = $this->MakeSign($params);
        if($params['sign'] == $sign){
            return true;
        }
        throw new \Exception("签名错误！");
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param $xml  需要post的xml数据
     * @param $url  url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second   url执行超时时间，默认30s
     * @return mixed
     * @throws WxPayException
     */
    private function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        //如果有配置代理这里就设置代理
        if(self::CURL_PROXY_HOST != "0.0.0.0"
            && self::CURL_PROXY_PORT != 0){
            curl_setopt($ch,CURLOPT_PROXY, self::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, self::CURL_PROXY_PORT);
        }
        curl_setopt($ch,CURLOPT_URL, $url);
        /*curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验*/
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            /*curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);*/

            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, $this->sslCert);
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, $this->sslKey);
            curl_setopt($ch, CURLOPT_CAINFO, $this->rootCa);

        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new \Exception("curl出错，错误码:$error");
        }
    }
}