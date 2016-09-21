{extend name="layout/base" /}
{block name="quote-css"}
    <link href="__CSS__/index.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
    <div class="page" id="page-home">
        <header class="bar bar-nav">
            <h1 class="title">果度商城</h1>
        </header>
        {include file="Layout:footer"/}
        <div class="content">
            <!-- 首页轮播图 开始 -->
            <section class="swiper-container swiper-home" id="swiper">
                <div class="swiper-wrapper">
                    {volist name="banners" id="vo"}
                        <div class="swiper-slide">
                            <a href="{$vo.link}">
                                <img class="swiper-img" src="{$vo.image|get_img}"/>
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
                            <li class="product-list-item" data-sku="{$vo.store_nums}" data-id="{$vo.id}"
                                data-unit="{$vo.unit}" data-cart_num="{$vo.cart_num|default=0}" >
                                <a class="flex">
                                    <div class="product-img">
                                        <img src="{$vo.cover_image|get_img}">
                                    </div>
                                    <div class="product-info flex-1">
                                        <h3 class="product_name">{$vo.name}</h3>
                                        <p class="product_intro">{$vo.intro}</p>
                                        <div class="product-cost widthScale80">
                                            <span class="product_price">
                                                <?php list($int,$decimal) = explode('.',10.28/*$vo['sell_price']*/);?>
                                                <sup>￥</sup>
                                                <em>{$int}.</em>
                                                <i>{$decimal}</i>
                                                <unit>/{$vo.unit}</unit>
                                            </span>
                                            <span class="product-sell_num">已售{$vo.sale}{$vo.unit}</span>
                                        </div>
                                        <div class="product-cart_add">
                                            <i class="icon icon-add cart_add"></i>

                                            <input class="cart_num" style="<?php if($vo['cart_num'] <= 0)echo 'display:none'?>"
                                                   type="tel" value="{$vo.cart_num}" onkeyup="this.value=this.value.replace(/\D/g,'')" maxlength="3"/>
                                            <i class="icon icon-minus cart_minus" style="<?php if($vo['cart_num'] <= 0)echo 'display:none'?>"></i>

                                        </div>
                                    </div>
                                </a>
                            </li>
                        {/volist}
                    </ul>
                    <!--<p class="text-center" style="line-height: 1.6rem;background-color: #f0f4f0">~没有更多商品了~</p>-->
                </div>
            </section>
        </div>
    </div>
{/block}

{block name="quote-js"}
    <script src="__JS__/cart.js"></script>
    <script src="__JS__/guodu.js"></script>
{/block}