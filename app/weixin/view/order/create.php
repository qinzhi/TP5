{extend name="Layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/order.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="__LIGHT7__/css/light7-swipeout.css">
{/block}
{block name="page"}
<div class="page page-order_create" id="page-order_create">
    <div class="content">
        <section class="order-address">
            <div class="no-address list-block" {if condition="$address"}style="display:none;"{/if}>
                <ul>
                    <li class="item-content item-link">
                        <div class="item-inner">
                            <div class="item-title">请填写收货地址</div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="address-area list-block media-list"  {if condition="!$address"}style="display:none;"{/if}>
                <ul>
                    <li class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">收件人：<span class="consignee">{$address.consignee??''}</span></div>
                                <div class="item-after"><span class="mobile">{$address.mobile??''}</span></div>
                            </div>
                            <div class="item-text"><span class="address">{$address.area_info??''}</span></div>
                        </div>
                    </li>
                </ul>
                <input type="hidden" name="address_id" value="{$address.id??0}" autocomplete="off"/>
            </div>
        </section>
        <section class="order-form">
            <div class="goods-info">
                <div class="content-block-title">购物清单</div>
                <div class="list-block media-list">
                    <ul>
                        <?php $total_num = $total_price = 0;?>
                        {volist name='products' id='product'}
                            <?php $total_num+= $product['cart_num'];$total_price+= ($product['sell_price'] * $product['cart_num']);?>
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
    {include file="order:address"/}
</div>
{/block}
{block name="quote-js"}
    <script type="text/javascript" src="__LIGHT7__/js/light7-city-picker.min.js" charset="utf-8"></script>
    <script type='text/javascript' src='__LIGHT7__/js/light7-swipeout.js' charset='utf-8'></script>
{/block}
{block name="js"}
<script>
    $(function(){
        $('.btn-submit').click(function () { /* 提交订单 */
            var address_id = $('.address-area').find('input[name="address_id"]').val();
            if(!address_id || address_id <= 0){
                return $.toast('请填写收货地址');
            }
        });
    });
</script>
{/block}