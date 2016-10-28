{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/order.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-order_create" id="page-order_index">
    <header class="bar bar-nav">
        <a class="icon icon-left pull-left"></a>
        <a class="icon icon-refresh pull-right"></a>
        <h1 class="title">全部订单</h1>
    </header>
    <div class="content">
        <div class="list-block cards-list">
            <ul>
                <li class="card">
                    <div class="card-header">
                        <span>下单时间: 2016-10-28 17:47</span>
                        <span>188</span>
                    </div>
                    <div class="card-content">
                        <div class="card-content-inner">
                            <ul>
                                <li class="flex">
                                   <div class="product-img">
                                       <img src="http://gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i3/TB10LfcHFXXXXXKXpXXXXXXXXXX_!!0-item_pic.jpg_250x250q60.jpg" style='width: 4rem;'>
                                   </div>
                                   <div class="product-info flex-1">
                                       <h3 class="product_name">小米手机</h3>
                                       <p>
                                           <span class="product_price">
                                                <sup>￥</sup>
                                                <em>10.</em>
                                                <i>28</i>
                                                <unit>/件</unit>
                                            </span>
                                       </p>
                                       <p>16G/白色</p>
                                       <span class="product_num">2</span>
                                   </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span>未支付</span>
                        <a href="#" class="link">更多</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
{/block}
{block name="js"}

{/block}