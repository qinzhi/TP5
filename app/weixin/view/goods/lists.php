{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-goods" id="page-goods_list">
    <header class="bar bar-nav">
        <div class="searchbar">
            <div class="search-input">
                <label class="icon icon-fangdajing" for="filtrate"></label>
                <input type="search" id='filtrate' autocomplete="off" placeholder='搜索商品'/>
            </div>
        </div>
    </header>
    <div class="bar bar-header-secondary">
        <ul id="nav" class="flex">
            <li class="active flex-1" data-field="" data-sort=""><div>综合</div></li>
            <li class="flex-1" data-field="sale" data-sort="desc"><div>销量</div></li>
            <li class="flex-1" data-field="is_new" data-sort="desc"><div>新品</div></li>
            <li class="flex-1" data-field="sell_price" data-sort="desc"><div>价格<i></i></div></li>
        </ul>
    </div>
    <div class="content infinite-scroll">
        <div class="product-content">
            <ul class="product-list clearfix"></ul>
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
        <li class="product-list-item" data-sku="{%goods.store_nums%}" data-id="{%goods.id%}"
            data-unit="{%goods.unit%}" data-price="{%goods.sell_price%}">
            <a class="flex" href="{%goods.url%}">
                <div class="product-img">
                    <img src="{%goods.cover_image%}">
                </div>
                <div class="product-info flex-1">
                    <h3 class="product_name">{%goods.name%}</h3>
                    <p class="product_intro">{%goods.intro%}</p>
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
                <div class="product-cart_add">
                    <i class="icon icon-gouwuche cart_add"></i>
                </div>
            </a>
        </li>
    {%/each%}
</script>
<script type="application/javascript">
    $(function () {
        var goods = {
            page: 1,
            limit: parseInt('{$limit}'),
            pageNum: 1,
            field: '',
            sort: '',
            keyword: '',
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
                $.post('{:url("goods/getGoodsList")}',params,function (result) {
                    instance.render(result.goodsList);
                });
                return this;
            },
            setField: function (field) {
                this.field = field;
                return this;
            },
            setSort: function (sort) {
                this.sort =  sort;
                return this;
            },
            setKeyword: function (keyword) {
                this.keyword = keyword;
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
        $('#filtrate').keydown(function (e) {
            if(e.keyCode == 13){
                var keyword = $(this).val().trim();
                goods.reset().setKeyword(keyword).getList();
            }
        });
    });
</script>
{/block}