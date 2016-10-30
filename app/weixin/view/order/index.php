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
                        <span>订单号: 201220161028174703</span>
                        <span>未支付</span>
                    </div>
                    <div class="card-content">
                        <a class="card-content-inner">
                            <ul class="product-list">
                                <li class="product-item flex">
                                   <div class="product-img">
                                       <img src="http://gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i3/TB10LfcHFXXXXXKXpXXXXXXXXXX_!!0-item_pic.jpg_250x250q60.jpg" style='width: 4rem;'>
                                   </div>
                                   <div class="product-info flex-1">
                                       <h4 class="product_name">小米手机</h4>
                                       <p>
                                           <span class="product_price price_com">10.28</span>
                                           <span class="product_unit">/件</span>
                                       </p>
                                       <p class="product_spec">16G/白色</p>
                                       <span class="product_num">x2</span>
                                   </div>
                                </li>
                            </ul>
                        </a>
                    </div>
                    <div class="card-footer no-border">
                        <span>数量：1</span>
                        <p>总金额: <span class="price_com">188</span></p>
                    </div>
                    <div class="card-footer">
                        <span>下单时间: 2016-10-28 17:47</span>
                        <a href="#" class="button button-fill button-warning">去支付</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
{/block}
{block name="js"}

{/block}