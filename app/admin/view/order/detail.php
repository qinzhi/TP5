{extend name="layout/base" /}
{block name="css"}
<style>
    .ncap-order-style {
        margin: 0 auto;
        width: 1000px;
    }
    .ncap-order-flow {
        background-color: #f5f5f5;
        border-radius: 5px;
        padding: 10px 0;
        position: relative;
        z-index: 1;
    }
    .ncap-order-flow ol {
        font-size: 0;
        list-style: none;
        padding: 0;
    }
    .ncap-order-flow .num5 li {
        width: 20%;
    }
    .ncap-order-flow li {
        display: inline-block;
        font-size: 12px;
        position: relative;
        text-align: center;
        vertical-align: top;
        z-index: 1;
    }
    .ncap-order-flow li.current h5 {
        color: #2cbca3;
        font-weight: 600;
    }
    .ncap-order-flow li h5 {
        font-size: 16px;
        font-weight: normal;
        height: 20px;
        line-height: 20px;
    }.ncap-order-flow li.current i {
         color: #2cbca3;
     }
    .ncap-order-flow li i {
        color: #d7d7d7;
        font-size: 18px;
        position: absolute;
        right: -8px;
        top: 12px;
        z-index: 1;
    }
    .ncap-order-flow li.current time {
        display: block;
    }
    .ncap-order-flow li time {
        background-color: #fff;
        border: 1px dotted #d6d6d6;
        border-radius: 10px;
        bottom: -20px;
        color: #777;
        display: none;
        font-size: 12px;
        height: 20px;
        left: 50%;
        line-height: 20px;
        margin-left: -70px;
        position: absolute;
        text-align: center;
        width: 140px;
        z-index: 1;
    }
</style>
{/block}
{block name="content"}
<div class="row no-margin">
    <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
        <div class="widget flat no-margin">
            <div class="widget-header widget-fruiter padding-bottom-5">
                <div class="ncap-order-style">
                    <div class="ncap-order-flow">
                        <ol class="num5">
                            <li class="current">
                                <h5>生成订单</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time>2016-04-05 14:51:26</time>
                            </li>
                            <li class="current">
                                <h5>完成付款</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time>2016-04-05 14:51:38</time>
                            </li>
                            <li class="">
                                <h5>商家发货</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time></time>
                            </li>
                            <li class="">
                                <h5>收货确认</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time></time>
                            </li>
                            <li class="">
                                <h5>完成评价</h5>
                                <time></time>
                            </li>
                        </ol>
                    </div>
                </div>
            </div><!--Widget Header-->
            <div class="widget-body plugins_goods-">
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-xs-12">
                        <div class="ncap-order-details">
                            <ul class="nav nav-tabs tabs-flat">
                                <li class="active">
                                    <a href="#tab-basic" data-toggle="tab">
                                        订单详情
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content tabs-flat">
                                <div class="tab-pane active" id="tab-basic">
                                    <div class="misc-info">
                                        <h4>下单/支付</h4>
                                        <dl>
                                            <dt>订单号：</dt>
                                            <dd>8000000000744201</dd>
                                            <dt>订单来源：</dt>
                                            <dd>PC端</dd>
                                            <dt>下单时间：</dt>
                                            <dd>2016-02-03 15:30:18</dd>
                                        </dl>
                                        <dl>
                                            <dt>支付单号：</dt>
                                            <dd>420507828618553666</dd>
                                            <dt>支付方式：</dt>
                                            <dd>站内余额支付</dd>
                                            <dt>支付时间：</dt>
                                            <dd>2016-02-03 15:30:38</dd>
                                        </dl>
                                    </div>
                                    <div class="addr-note">
                                        <h4>购买/收货方信息</h4>
                                        <dl>
                                            <dt>买家：</dt>
                                            <dd>hhr002</dd>
                                            <dt>联系方式：</dt>
                                            <dd>13286868686</dd>
                                        </dl>
                                        <dl>
                                            <dt>收货地址：</dt>
                                            <dd>测试账号测试数据测试数据测试数据测试&nbsp;&nbsp;,&nbsp;广东广州市萝岗区 化信大厦</dd>
                                        </dl>
                                        <dl>
                                            <dt>发票信息：</dt>
                                            <dd>
                                            </dd>
                                        </dl>
                                        <dl>
                                            <dt>买家留言：</dt>
                                            <dd></dd>
                                        </dl>
                                    </div>
                                    <div class="goods-info">
                                        <h4>商品信息</h4>
                                        <table>
                                            <thead>
                                            <tr>
                                                <th colspan="2">商品</th>
                                                <th>单价</th>
                                                <th>数量</th>
                                                <th>一级</th>
                                                <th>二级</th>
                                                <th>三级</th>
                                                <th>四级</th>
                                                <th>优惠活动</th>
                                                <th>佣金比例</th>
                                                <th>商品技术服务费</th>
                                                <th>运费技术服务费</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="w30"><div class="goods-thumb"><a href="http://test.cunctao.com/index.php?act=goods&amp;goods_id=103765" target="_blank"><img alt="" src="http://test.cunctao.com/data/upload/shop/common/default_goods_image_60.gif"> </a></div></td>
                                                <td style="text-align: left;"><a href="URL_WHOLESALE/index.php?act=goods&amp;goods_id=103765" target="_blank">瑞士瑞动-单肩包 MT-5786-02T00公文包休闲包电脑包 黑色 21.5*18.8CM</a></td>
                                                <td class="w80">￥66.00</td>
                                                <td class="w60">5</td>

                                                <td>箱包皮具</td>
                                                <td>男士包袋</td>
                                                <td>无</td>
                                                <td>无</td>
                                                <td class="w100"></td>
                                                <td class="w60">0%</td>
                                                <td class="w80">0.00</td>
                                                <td></td>
                                            </tr>
                                            <!-- S 赠品列表 -->
                                            <!-- E 赠品列表 -->
                                            </tbody>
                                            <!-- S 促销信息 -->
                                            <!-- E 促销信息 -->
                                        </table>
                                    </div>
                                    <div class="total-amount">
                                        <h3>订单总额：<strong class="red_common">￥330.00</strong></h3>
                                        <h4>(运费：免运费)</h4>
                                        (退款：￥330.00)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--Widget Body-->
        </div><!--Widget-->
    </div>
</div>
{/block}