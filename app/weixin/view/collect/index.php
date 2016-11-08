{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-goods" id="page-collect_list">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="{:url('member/index')}">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">我的收藏</h1>
    </header>
    {include file="Layout:footer"/}
    <div class="content infinite-scroll">
        <div class="product-content">
            <div class="product-list clearfix"></div>
            <div class="infinite-scroll-preloader">
                <div class="preloader"></div>
            </div>
            <p class="text-center loaded-tip">~没有更多商品了~</p>
        </div>
        {include file="public:purchases"}
    </div>
</div>
{/block}
{block name="js"}
<script type="text/html" id="listTpl">
    {%each goodsList as goods%}
    <div class="card goods-info" data-sku="{%goods.store_nums%}" data-id="{%goods.id%}"
         data-unit="{%goods.unit%}" data-price="{%goods.sell_price%}">
        <div class="card-content">
            <a class="card-content-inner flex" href="{%goods.url%}">
                <div class="product-img">
                    <img src="{%goods.cover_image%}"/>
                </div>
                <div class="product-info flex-1">
                    <h3 class="product_name">{%goods.name%}</h3>
                    <div class="product-cost">
                                <span class="product_price">
                                    <sup>￥</sup>
                                    {%price = goods.sell_price.split('.')%}
                                    <em>{%price[0]%}.</em>
                                    <i>{%price[1]%}</i>
                                    <unit>/{%goods.unit%}</unit>
                                </span>
                        <span class="product-sell_num">已售{%goods.sale%}{%goods.unit%}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="card-footer">
            <div class="flex-1"><a class="btn-card_primary cart_add" href="javascript:;">加入购物车</a></div>
            <div class="flex-1"><a class="btn-card_danger btn-del" href="javascript:;">删除</a></div>
        </div>
    </div>
    {%/each%}
</script>
<script type="application/javascript">
    $(function () {
        var goods = {
            page: 1,
            limit: parseInt('{$limit}'),
            pageNum: 1,
            preloader: $('.infinite-scroll-preloader'),
            loaded_tip: $('.loaded-tip'),
            product_list_html: $('.product-list'),
            getList: function () {
                var instance = this;
                var params = {};
                params.page = this.page;
                if(this.field) params.field = this.field;
                if(this.sort) params.sort = this.sort;
                if(this.keyword) params.keyword = this.keyword;
                this.loaded_tip.hide();
                $.post('{:url("collect/getGoodsList")}',params,function (result) {
                    instance.render(result.goodsList);
                });
                return this;
            },
            setPage: function (page) {
                this.page = page;
                return this;
            },
            setPageNum: function (pageNum) {
                this.pageNum = pageNum;
                return this;
            },
            reset: function () {
                this.page = 1;
                this.pageNum = 1;
                this.keyword = '';
                this.field = '';
                this.sort = '';
                this.product_list_html.html('');
                this.preloader.show();
                return this;
            },
            render: function (goodsList) {
                if(goodsList.length > 0){
                    this.product_list_html.html(template('listTpl',{goodsList:goodsList}));
                }
                if(goodsList.length < this.limit){
                    this.loaded_tip.show();
                    this.preloader.hide();
                }else{
                    this.page++;
                }
            }
        }.getList();
        $('#nav li').click(function () {
            $(this).parent().find('.active').removeClass('active');
            $(this).addClass('active');
            goods.reset().setField($(this).data('field')).setSort($(this).data('sort')).getList();
        });
        $(document).on('infinite', '.infinite-scroll',function() {
            goods.getList();
        });
        $(document).on('click', '.btn-del',function() {
            var self = this;
            $.confirm('确认是否移除收藏?', function () {
                var card = $(self).closest('.card');
                var id = card.data('id');
                $.showIndicator();
                $.post('{:url("goods/collect")}',{goods_id:id},function (result) {
                    $.hideIndicator();
                    $.toast(result.msg);
                    if(result.code == 1){
                        card.remove();
                    }
                });
            });
        });
    });
</script>
{/block}