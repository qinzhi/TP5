{extend name="Layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/cart.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
    <div class="page" id="page-cart">
        {include file="Layout:footer"/}
        <div class="content">
            <section class="empty-cart-warp">
                <div class="empty-cart">
                    <i class="icon icon-cart"></i>
                    <p class="cart-tip">购物车空空的 赶快填饱它吧~</p>
                    <a class="btn btn-primary btn-empty-cart" href="{:url('/weixin/category')}">去进货吧</a>
                </div>
            </section>
        </div>
    </div>
{/block}