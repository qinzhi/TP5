<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <block name="title">
        <title>{$title?:'微信支付'}</title>
    </block>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="__LIGHT7__/css/light7.min.css">
    <link rel="stylesheet" href="__LIGHT7__/css/light7-swiper.min.css">
    <link rel="stylesheet" href="__CSS__/pay.css">
    <script type='text/javascript' src='__STATIC__/js/jquery-2.1.4.min.js' charset='utf-8'></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
<div class="page page-home page-pay_weixin" id="page-pay_weixin">
    <div class="content content-pay_weixin">
        <div id="payDom" class="cardexplain">
            <ul class="round">
                <li class="head">
                    <span>支付信息</span>
                </li>
                <li class="nob">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                        <tr>
                            <th width="30%">订单号</th>
                            <td>{$order.order_sn}</td>
                        </tr>
                    </table>
                </li>
                <li class="nob">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                        <tr>
                            <th width="30%">下单时间</th>
                            <td>{$order.add_time|date='Y-m-d H:i:s',###}</td>
                        </tr>
                    </table>
                </li>
                <li class="nob">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                        <tr>
                            <th width="30%">金额</th>
                            <td class="price">{$order.pay_price}元</td>
                        </tr>
                    </table>
                </li>
            </ul>
            <div class="footReturn text-center">
                <input type="button" onclick="callpay()" class="button button-fill button-pay" value="点击进行微信支付" />
            </div>
        </div>

        <div id="failDom" class="cardexplain">
            <ul class="round">
                <li class="head">
                    <span>支付结果</span>
                </li>
                <li class="nob">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                        <tr>
                            <th>支付失败</th>
                            <td>
                                <div id="failRt"></div>
                            </td>
                        </tr>
                    </table>
                </li>
            </ul>
            <div class="footReturn text-center">
                <input type="button" onclick="callpay()" class="button button-fill button-pay" value="重新进行支付" />
            </div>
        </div>

        <div id="successDom" class="cardexplain">
            <ul class="round">
                <li class="head">
                    <span>支付成功</span>
                </li>
                <li class="nob">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
                        <tr>
                            <th>您已支付成功，页面正在跳转...</th>
                            <td>
                                <div id="failRt"></div>
                            </td>
                        </tr>
                    </table>
                </li>
            </ul>
        </div>
    </div>
</div>
<script type='text/javascript' src='__LIGHT7__/js/light7.min.js' charset='utf-8'></script>
<script type='text/javascript' src='__LIGHT7__/js/i18n/cn.min.js' charset='utf-8'></script>
<script type="application/javascript">
    (function wx_init(){
        $.ajax({
            type: 'POST',
            url: '',
            data: { url: encodeURIComponent(window.location.href)},
            success: function(config){
                wx.config({
                    debug: false,
                    appId: config.appid,
                    timestamp: config.timestamp,
                    nonceStr: config.nonceStr,
                    signature: config.signature,
                    jsApiList: ['hideOptionMenu']
                });
            }
        });
    })();
    wx.ready(function(){
        wx.hideOptionMenu();
    });
    function callpay() {
        WeixinJSBridge.invoke('getBrandWCPayRequest', '', function (res) {
            WeixinJSBridge.log(res.err_msg);

            $('#payDom').hide();
            if (res.err_msg == 'get_brand_wcpay_request:ok') {
                $('#successDom').show();
                setTimeout(function () {
                    window.location.href = '';
                }, 3000);
            }else {
                $('#failDom').show();
                var msg = [];
                if(res.err_code) msg[msg.length] = res.err_code;
                if(res.err_desc) msg[msg.length] = res.err_desc;
                if(res.err_msg) msg[msg.length] = res.err_msg;
                $('#failRt').html( msg.join('|'));
            }
        });
    }
</script>
</body>
</html>