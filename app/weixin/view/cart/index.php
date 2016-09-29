{extend name="Layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/cart.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
    <div class="page" id="page-cart">
        <header class="bar bar-nav">
            
        </header>
        <div class="bar bar-footer bar-cart-footer">
            <div class="pull-left cart-total">
                <p> 共<span class="quantity">{$totalNum}</span>件</p>
                <p>货款总计¥ <span class="cash">{$totalPrice}</span>（不含运费）</p>
            </div>
            <button class="button button-fill button-clearing pull-right">
                去结算
            </button>
        </div>

        <div class="content">
            <ul class="cart-list">
                {volist name="products" id="vo"}
                    <li class="flex" data-sku="{$vo.store_nums}" data-id="{$vo.cart_id}"
                        data-unit="{$vo.unit}" data-cart_num="{$vo.cart_num|default=0}">
                        <div class="goods-img">
                            <img src="{$vo.cover_image|get_img}">
                        </div>
                        <div class="goods-info flex-1">
                            <h5 class="goods-name">{$vo.name}</h5>
                            <div class="goods-buy flex">
                                <div class="goods-rule flex-1">
                                    <p>1</p>
                                    <p>1</p>
                                </div>
                                <div class="goods-action">
                                    <div class="action-group">
                                        <a class="amount-up cart_add" href="javascript:;">
                                            <i class="icon icon-jiahao"></i>
                                        </a>
                                        <input class="amount-input cart_num" type="number" pattern="d*" maxlength="3" value="{$vo.cart_num}" autocomplete="off">
                                        <a class="amount-down cart_minus" href="javascript:;">
                                            <i class="icon icon-jianhao"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                {/volist}
            </ul>
        </div>
    </div>
{/block}
{block name="js"}
    <script>
        $(function(){
            var purchase = null;
            $('.cart_num').bind('blur',function(){
                var num = parseInt(this.value);
                purchase = new purchases($(this).closest('li'));
                purchase.setTips('购物车正在处理...').setNum(num).updateCart();
            });
            $('.cart_add').bind('click',function(){
                purchase = new purchases($(this).closest('li'));
                purchase.setTips('正在加入购物车...').setNum(purchase.num + 1).updateCart();
            });
            $('.cart_minus').bind('click',function(){
                purchase = new purchases($(this).closest('li'));
                if(purchase.num > 0){
                    if(purchase.num == 1){
                        purchase.goods.remove();
                    }
                    purchase.setTips('购物车正在处理...').setNum(purchase.num - 1).updateCart();
                }
            });
        });
    </script>
{/block}