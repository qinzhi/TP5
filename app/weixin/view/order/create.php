{extend name="Layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/order.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-order_create" id="page-order_create">
    <div class="content">
        <section class="order-address">
            <div class="no-address list-block">
                <ul>
                    <li class="item-content item-link">
                        <div class="item-inner">
                            <div class="item-title">请填写收货地址</div>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <section class="order-form">
            <div class="goods-info">
                <div class="content-block-title">购物清单</div>
                <div class="list-block media-list">
                    <ul>
                        <?php $total_num = $total_price = 0;?>
                        {volist name='products' id='product'}
                            <?php $total_num+= $product['cart_num'];$total_price+=$product['sell_price'];?>
                            <li class="item-content">
                                <div class="item-media"><img src="{$product.cover_image|get_img}" style='width: 4rem;height: 4rem;'></div>
                                <div class="item-inner">
                                    <div class="item-title-row">
                                        <div class="item-title">{$product.name}</div>
                                    </div>
                                    <div class="item-subtitle item-price">￥{$product.sell_price}</div>
                                    <div class="item-text">
                                        <?php
                                            $spec = $product['spec_array'];
                                            if(!empty($spec)){
                                                $spec = json_decode($spec,true);
                                                foreach ($spec as $v){
                                                    echo $v['name'] . ': ' . $v['value'] . '; ';
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="item-num absolute">x{$product.cart_num}</div>
                                </div>
                            </li>
                        {/volist}
                    </ul>
                    <div class="order-goods-statistics">
                        <p>共<span class="total-num">{$total_num}</span>件商品</p>
                        <p>合计：<span class="total-price">￥{$total_price}</span></p>
                </div>
                </div>
            </div>
            <div class="list-block order-pay-type">
                <ul>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">支付方式</div>
                            <div class="item-after">微信支付</div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="list-block order-amount">
                <ul>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">商品金额</div>
                            <div class="item-after item-price">￥{$total_price}</div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">运费</div>
                            <div class="item-after item-price">+￥0.00</div>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
    </div>
    <div class="bar bar-footer bar-order_create flex">
        <p class="order-amount flex-1">实付<span>¥{$total_price}</span>(不含运费)</p>
        <button class="button button-fill btn-submit">提交订单</button>
    </div>
    <section class="order-address_add">
        <div class="all-shade fade_toggle"></div>
        <div class="address-panel active">
            <header class="address-header"><h4>编辑收货地址</h4></header>
            <form class="address-area list-block" autocomplete="off">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="text" name="name" placeholder="姓名" maxlength="8">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="tel" name="tel" placeholder="联系电话" maxlength="11">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="text" name="address" id="city-picker" placeholder="省、市、区/县">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </form>
            <div class="address-action">
                <div class="flex">
                    <div class="flex-1">
                        <a class="btn btn-primary no-radius btn-block address-ok">保存</a>
                    </div>
                </div>
            </div>
            <div class="panel-close">
                <i class="icon icon-cha action-close"></i>
            </div>
        </div>
    </section>
</div>
{/block}
{block name="quote-js"}
    <script type="text/javascript" src="__LIGHT7__/js/light7-city-picker.js" charset="utf-8"></script>
{/block}
{block name="js"}
<script>
    $(function () {
        $("#city-picker").cityPicker({
            toolbarTemplate: '<header class="bar bar-nav"><button class="button button-link pull-right close-picker">确定</button></header>',
            cssClass: 'address-picker'
        });
    });
</script>
{/block}