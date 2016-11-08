{extend name="layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/index.css" rel="stylesheet" type="text/css">
    <link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
    <div class="page" id="page-home">
        <header class="bar bar-nav">
            <div class="searchbar">
                <div class="search-input">
                    <label class="icon icon-fangdajing" for="filtrate"></label>
                    <input id="filtrate" placeholder="搜索商品" type="search">
                </div>
            </div>
        </header>
        {include file="layout:footer"/}
        <div class="content">
            <!-- 首页轮播图 开始 -->
            <section class="swiper-container swiper-home" id="swiper">
                <div class="swiper-wrapper">
                    {volist name="banners" id="vo"}
                        <div class="swiper-slide">
                            <a href="{$vo.link}">
                                <img class="swiper-img" src="{$vo.image|get_img_url}"/>
                            </a>
                        </div>
                    {/volist}
                </div>
                <div class="swiper-pagination"></div>
            </section>
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

            <!-- 快速入口 -->
            <section class="fast-entrance"></section>

            <section class="product-area">
                <div class="product-title clearfix">
                    <h4 class="pull-left">农庄直供</h4>
                    <i class="pull-right icon icon-right"></i>
                </div>
                <div class="product-content">
                    <ul class="product-list clearfix">
                        {volist name="goods" id="vo"}
                            <li class="product-list-item goods-info" data-sku="{$vo.store_nums}" data-id="{$vo.id}"
                                data-unit="{$vo.unit}" data-price="{$vo.sell_price}">
                                <a class="flex" href="{:url('goods/detail',['id'=>$vo['id']])}">
                                    <div class="product-img">
                                        <img src="{$vo.cover_image|get_img_url}">
                                    </div>
                                    <div class="product-info flex-1">
                                        <h3 class="product_name">{$vo.name}</h3>
                                        <p class="product_intro">{$vo.intro}</p>
                                        <div class="product-cost">
                                            <span class="product_price">
                                                <?php list($int,$decimal) = explode('.',10.28/*$vo['sell_price']*/);?>
                                                <sup>￥</sup>
                                                <em>{$int}.</em>
                                                <i>{$decimal}</i>
                                                <unit>/{$vo.unit}</unit>
                                            </span>
                                            <span class="product-sell_num">已售{$vo.sale}{$vo.unit}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="product-cart_add">
                                    <i class="icon icon-gouwuche cart_add"></i>
                                </div>
                            </li>
                        {/volist}
                    </ul>
                    <!--<p class="text-center" style="line-height: 1.6rem;background-color: #f0f4f0">~没有更多商品了~</p>-->
                </div>
            </section>

            {include file="public:purchases"}
        </div>
    </div>
{/block}
{block name="js"}
<script>
    $(function () {
        $('#filtrate').keydown(function (e) {
            if(e.keyCode == 13){
                var keyword = $(this).val().trim();
                goods.reset().setKeyword(keyword).getList();
            }
        });
    });
</script>
{/block}