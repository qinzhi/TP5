{extend name="layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/cart.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
    <div class="page" id="page-cart">
        <header class="bar bar-nav">
            <div class="flex cart-heard">
                <label class="label-checkbox">
                    <input type="checkbox" name="selectAll" id="selectAll" autocomplete="off">
                    <div class="item-media">
                        <i class="icon icon-form-checkbox"></i>
                        <span class="s-text">全选</span>
                    </div>
                </label>
                <div class="flex-1"></div>
            </div>
        </header>
        <div class="bar bar-footer bar-cart-footer">
            <div class="pull-left cart-total">
                <p> 共<span class="quantity">0</span>件</p>
                <p>货款总计¥ <span class="cash">0.00</span>（不含运费）</p>
            </div>
            <button class="button button-fill btn-clearing pull-right">去结算</button>
        </div>
        <div class="content">
            <ul class="cart-list">
                {volist name="products" id="vo"}
                    <li class="flex" data-sku="{$vo.store_nums}" data-id="{$vo.cart_id}" data-unit="{$vo.unit}"
                        data-product_id="{$vo.product_id}" data-price="{$vo.sell_price}">
                        <label class="label-checkbox">
                            <input type="checkbox" name="chk-id" value="{$vo.cart_id}" autocomplete="off" {if condition="$vo.is_selected eq 1"}checked{/if}>
                            <div class="item-media">
                                <i class="icon icon-form-checkbox"></i>
                            </div>
                        </label>
                        <div class="goods-img">
                            <img src="{$vo.cover_image|get_img}">
                        </div>
                        <div class="goods-info flex-1">
                            <h3 class="goods-name">{$vo.name}</h3>
                            <p class="goods-price">￥<span>{$vo.sell_price}</span></p>
                            <?php if(!empty($vo['spec_array'])):?>
                                <?php $spec_array = json_decode($vo['spec_array'],true);?>
                                <p class="goods-spec">
                                    {volist name="spec_array" id="v"}
                                    <span>{$v.value}</span>
                                    {/volist}
                                </p>
                            <?php endif;?>
                            <div class="goods-action">
                                <div class="action-group">
                                    <a class="amount-up cart_add" href="javascript:;">
                                        <i class="icon icon-jiahao"></i>
                                    </a>
                                    <input class="amount-input cart_num" name="cart_num" type="number" pattern="d*" maxlength="3" value="{$vo.cart_num}" readonly>
                                    <a class="amount-down cart_minus" href="javascript:;">
                                        <i class="icon icon-jianhao"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                {/volist}
            </ul>
        </div>
    </div>
{/block}
{block name="quote-js"}
    <script src="__JS__/cart.js"></script>
{/block}