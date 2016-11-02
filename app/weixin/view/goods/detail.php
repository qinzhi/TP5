{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-goods" id="page-goods_detail">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="javascript:history.go(-1);">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">商品详情</h1>
    </header>
    <div class="bar bar-footer footer-goods_detail">
        <ul class="flex">
            <li>
                <a href="{:url('cart/index')}">
                    <span class="icon icon-gouwuche"></span><span class="badge" id="cart_num">{$cartNum|default=0}</span>
                </a>
            </li>
            <li>
                <a href="{:url('member/index')}">
                    <span class="icon icon-quanyonghu"></span>
                </a>
            </li>
            <li class="flex-1"><a class="button button-fill button-warning">加入购物车</a></li>
        </ul>
    </div>
    <div class="content">
        <!-- 首页轮播图 开始 -->
        <section class="swiper-container swiper-home" id="swiper">
            <div class="swiper-wrapper">
                {volist name="images" id="vo"}
                <div class="swiper-slide">
                    <img class="swiper-img" src="{$vo.image|get_img}"/>
                </div>
                {/volist}
            </div>
            <div class="swiper-pagination"></div>
        </section>
        <section class="product_info">
            <div class="product-title flex">
                <h4 class="flex-1">【整件批发】山东富士75#（整件10斤）</h4>
                <div class="collect">
                    <i class="icon icon-shoucang"></i>
                    <p>收藏</p>
                </div>
            </div>
            <div class="product_price flex">
                <p class="flex-1">
                    价格:
                    <span class="sell_price">
                        <sup>￥</sup>
                        <em>10.</em>
                        <i>28</i>
                        <unit>/件</unit>
                    </span>
                </p>
                <p class="text-right product-sale">销量0件</p>
            </div>
        </section>
        <div class="list-block product_spec">
            <ul>
                <li class="item-content item-link">
                    <div class="item-inner">
                        <div class="item-title">请选择 内存 颜色</div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="product_evaluate">
            <h4 class="evaluate_name">评价晒单(1)</h4>
            <ul class="evaluate_list">
                <li class="evaluate_item">
                    <h5>
                        <img class="avator" src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM6dkNLaHQBND38jBNUPmib8dxd6z6Pvh6DRrUks3AgmrmqSibv1RFMEQiaJ12p09M92JQt8zf14dwH4CZgEEo3eSXHxxmuhAEWPJU/64"/> 啊秦智
                        <p class="evaluate_time pull-right">2016-11-02</p>
                    </h5>
                    <p class="evaluate_content">苹果压坏了5个</p>
                </li>
                <li class="evaluate_item">
                    <h5>
                        <img class="avator" src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM6dkNLaHQBND38jBNUPmib8dxd6z6Pvh6DRrUks3AgmrmqSibv1RFMEQiaJ12p09M92JQt8zf14dwH4CZgEEo3eSXHxxmuhAEWPJU/64"/> 啊秦智
                        <p class="evaluate_time pull-right">2016-11-02</p>
                    </h5>
                    <p class="evaluate_content">苹果压坏了5个</p>
                </li>
                <li class="evaluate_item">
                    <h5>
                        <img class="avator" src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM6dkNLaHQBND38jBNUPmib8dxd6z6Pvh6DRrUks3AgmrmqSibv1RFMEQiaJ12p09M92JQt8zf14dwH4CZgEEo3eSXHxxmuhAEWPJU/64"/> 啊秦智
                        <p class="evaluate_time pull-right">2016-11-02</p>
                    </h5>
                    <p class="evaluate_content">苹果压坏了5个</p>
                </li>
            </ul>
            <a class="button button-warning btn-evaluate">查看全部评论</a>
        </div>
        <script>
            $(function(){
                $('.swiper-container').swiper({
                    autoplay : 3000,
                    loop: true,
                    pagination : '.swiper-pagination',
                    paginationType : 'bullets',
                    paginationClickable :true
                });
            });
        </script>
        <!-- 首页轮播图 结束 -->

    </div>
</div>
{/block}