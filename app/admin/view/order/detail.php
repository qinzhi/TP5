{extend name="layout/base" /}
{block name="css"}
<style>
    .order-detail-area .widget > .widget-header,.order-detail-area .widget .widget-body{
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.3);
    }
    .ncap-order-style {
        margin: 0 auto;
        width: 1000px;
        margin-top: -9px;
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
        color: #53a93f;
        font-weight: 600;
    }
    .ncap-order-flow li h5 {
        font-size: 16px;
        font-weight: normal !important;
        height: 20px;
        line-height: 20px;
    }.ncap-order-flow li.current i {
         color: #53a93f;
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
    .ncap-order-details .misc-info,.ncap-order-details .addr-note{
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e6e6e6;
    }
    .ncap-order-details h4{
        font-weight: bold !important;
        font-size: 14px;
    }
    .ncap-order-details dl{
        margin-bottom: 5px;
    }
    .ncap-order-details dt,.ncap-order-details dd{
        display: inline-block;
    }
    .ncap-order-details dt{
        width: 10%;
        text-align: right;
        color: #999;
    }
    .ncap-order-details dd{
        min-width: 20%;
        text-align: left;
        color: #333;
    }
    .ncap-order-details .total-amount{
        padding: 10px 0;
        text-align: right;
    }
    .ncap-order-details .total-amount h3{
        color: #777;
        font-size: 16px;
        font-weight: normal;
        line-height: 24px;
    }
    .ncap-order-details .total-amount .red_common{
        color: #d73d32;
        font-size: 20px;
    }
</style>
{/block}
{block name="content"}
<div class="row margin-20 order-detail-area">
    <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
        <div class="widget flat no-margin">
            <div class="widget-header">
                <div class="widget-buttons margin-5">
                    <button class="btn btn-success">一键发货</button>
                </div>
            </div>
            <div class="widget-header widget-fruiter padding-bottom-5">
                <div class="ncap-order-style">
                    <div class="ncap-order-flow">
                        <ol class="num5">
                            <li class="current">
                                <h5>生成订单</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time>{$order.add_time|date='Y-m-d H:i:s',###}</time>
                            </li>
                            <li class="{if condition='$order.payment_time gt 0'}current{/if}">
                                <h5>完成付款</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time>{$order.payment_time|date='Y-m-d H:i:s',###}</time>
                            </li>
                            <li class="{if condition='$order.send_status eq 2'}current{/if}">
                                <h5>商家发货</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time>{if condition='$order.send_status eq 2'}{$order.send_time|date='Y-m-d H:i:s',###}{/if}</time>
                            </li>
                            <li class="{if condition='$order.receive_status eq 2'}current{/if}">
                                <h5>收货确认</h5>
                                <i class="fa fa-arrow-circle-right"></i>
                                <time>{if condition='$order.receive_status eq 2'}{$order.receive_time|date='Y-m-d H:i:s',###}{/if}</time>
                            </li>
                            <li class="{if condition='$order.evaluation_status eq 2'}current{/if}">
                                <h5>完成评价</h5>
                                <time>{if condition='$order.evaluation_status eq 2'}{$order.evaluation_time|date='Y-m-d H:i:s',###}{/if}</time>
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
                                            <dd>{$order.order_sn}</dd>
                                            <dt>订单来源：</dt>
                                            <dd>{$order.source_text}</dd>
                                            <dt>下单时间：</dt>
                                            <dd>{$order.add_time|date='Y-m-d H:i:s',###}</dd>
                                        </dl>
                                        <dl>
                                            <dt>支付单号：</dt>
                                            <dd>420507828618553666</dd>
                                            <dt>支付类型：</dt>
                                            <dd>{$order.pay_type_text}</dd>
                                            <dt>支付时间：</dt>
                                            <dd>2016-02-03 15:30:38</dd>
                                        </dl>
                                    </div>
                                    <div class="addr-note">
                                        <h4>购买/收货方信息</h4>
                                        <dl>
                                            <dt>收货人：</dt>
                                            <dd>{$order.consignee}</dd>
                                            <dt>收货人手机：</dt>
                                            <dd>{$order.mobile}</dd>
                                        </dl>
                                        <dl>
                                            <dt>收货地址：</dt>
                                            <dd>{$order.area_info}</dd>
                                        </dl>
                                        <dl>
                                            <dt>买家留言：</dt>
                                            <dd>{$order.note}</dd>
                                        </dl>
                                    </div>
                                    <div class="goods-info">
                                        <h4>商品信息</h4>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <th>商品名称</th>
                                                <th>商品规格</th>
                                                <th>商品单价</th>
                                                <th>商品数量</th>
                                                <th>运费</th>
                                                <th>总计</th>
                                            </thead>
                                            <tbody>
                                                {volist name="orderList" id="vo"}
                                                    <tr>
                                                        <td><a href="javascript:;" target="_blank">{$vo.goods_name}</a></td>
                                                        <td>

                                                            <?php if(!empty($vo['product_spec_array'])):
                                                                $spec_arr = json_decode($vo['product_spec_array'],true);
                                                                foreach ($spec_arr as $spec):
                                                                    echo $spec['name'] . ":". $spec['value'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                endforeach;
                                                            endif;?>

                                                        </td>
                                                        <td>{$vo.product_sell_price}</td>
                                                        <td>{$vo.product_buy_num}</td>
                                                        <td>{$vo.product_freight}</td>
                                                        <td>{$vo.product_buy_num*$vo.product_sell_price+$vo.product_freight}</td>
                                                    </tr>
                                                {/volist}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="total-amount">
                                        <h3>支付总额：<strong class="red_common">￥{$order.pay_price}</strong></h3>
                                        <h3>订单总额：<strong class="red_common">￥{$order.goods_amount}</strong></h3>
                                        <h5>(运费：免运费)</h5>
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