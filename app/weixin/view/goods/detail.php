{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="title"}
<title>{$goods.name}</title>
{/block}
{block name="page"}
<div class="page page-goods goods-info" id="page-goods_detail" data-id="{$goods.id}"
     data-price="{$goods.sell_price}" data-img="{$goods.cover_image|get_img}" data-is_memory="1">
    <!--<header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="javascript:history.go(-1);">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">商品详情</h1>
    </header>-->
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
            <li class="flex-1"><a class="button button-fill button-warning cart_add">加入购物车</a></li>
        </ul>
    </div>
    <div class="content infinite-scroll">
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
        </section>
        <section class="product_info">
            <div class="product-title flex">
                <h4 class="flex-1 product_name">{$goods.name}</h4>
                <div class="collect {$is_favorite==1?'active':'';}" id="collect" data-is_favorite="{$is_favorite}">
                    <i class="icon icon-shoucang"></i>
                    <p class="label">{$is_favorite==1?'已收藏':'收藏';}</p>
                </div>
            </div>
            <div class="product_price flex">
                <p class="flex-1">
                    价格:
                    <?php list($int,$decimal) = explode('.',$goods['sell_price']);?>
                    <span class="sell_price">
                        <sup>￥</sup>
                        <em>{$int}.</em>
                        <i>{$decimal}</i>
                        <unit>/{$goods.unit}</unit>
                    </span>
                </p>
                <p class="text-right product-sale">销量{$goods.sale}件</p>
            </div>
        </section>

        {if condition="$product.spec_array neq ''"}
            <?php $spec_arr = json_decode($product['spec_array'],true);?>
            <div class="list-block product_spec">
                <ul>
                    <li class="item-content cart_add item-link">
                        <div class="item-inner">
                            <div class="item-title product_property">请选择{volist name="spec_arr" id="vo"} {$vo.name}{/volist}</div>
                        </div>
                    </li>
                </ul>
            </div>
        {/if}
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
        <div class="product_describe">
            <div class="buttons-tab">
                <a href="#tab-detail" class="tab-link active button">图文详情</a>
                <a href="#tab-attr" class="tab-link button">商品属性</a>
            </div>
            <div class="tabs-content">
                <div class="tabs">
                    <div id="tab-detail" class="tab active">
                        {$goods.detail}
                    </div>
                    <div id="tab-attr" class="tab">
                        <div class="list-block">
                            <ul>
                                {volist name="attr" id="vo"}
                                    <li class="item-content">
                                        <div class="item-inner">
                                            <div class="item-title">{$vo.name}</div>
                                            <div class="item-after">{$vo.attr_value}</div>
                                        </div>
                                    </li>
                                {/volist}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {include file="public:purchases"}
    </div>
</div>
{/block}
{block name="js"}
<script>
    $(function () {
        $('.content').scroll(function(e){
            var btn_tab = $('.product_describe .buttons-tab');
            var tab_con = $('.product_describe .tabs-content');
            var bh = btn_tab.height();
            var bt = btn_tab.offset().top;
            var ct = tab_con.offset().top;
            if(bt <= 0){
                if(!btn_tab.hasClass('fixed-tab'))
                    btn_tab.addClass('fixed-tab');
            }
            if(ct + bh >= 0){
                if(btn_tab.hasClass('fixed-tab'))
                    btn_tab.removeClass('fixed-tab');
            }
        });
        $('#collect').click(function () {
            $.showIndicator();
            var self = $(this);
            $.post('{:url("goods/collect")}',{goods_id:'{$goods.id}'},function (result) {
                $.hideIndicator();
                $.toast(result.msg);
                if(result.code == 1){
                    if(result.action == 'cancel'){
                        self.removeClass('active');
                    }else{
                        self.addClass('active');
                    }
                    self.find('.label').text(result.label);
                }
            });
        });
    });
</script>
{/block}