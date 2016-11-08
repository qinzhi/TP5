{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/order.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-order_create" id="page-order_index">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="{:url('member/index')}">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">
            <a href="javascript:;" id="bar-title" class="open-popover" data-popover=".popover-order_status">
                {$title}
                <span class="icon icon-down"></span>
            </a>
        </h1>
    </header>

    <div class="content">
        <div class="list-block cards-list">
            <ul class="order-list"></ul>
        </div>
        <div class="infinite-scroll-preloader">
            <div class="preloader"></div>
        </div>
        <p class="text-center loaded-tip">~没有更多订单了~</p>
    </div>
</div>
<div class="popover popover-order_status">
    <div class="popover-angle"></div>
    <div class="popover-inner">
        <div class="list-block">
            <ul>
                <li><a href="javascript:;" class="list-button item-link close-popover" data-status="0">全部订单({$count.count|default=0})</a></li>
                <li><a href="javascript:;" class="list-button item-link close-popover" data-status="1">待支付({$count.no_pay_count|default=0})</a></li>
                <li><a href="javascript:;" class="list-button item-link close-popover" data-status="2">待发货({$count.pay_count|default=0})</a></li>
                <li><a href="javascript:;" class="list-button item-link close-popover" data-status="3">待收货({$count.receive_count|default=0})</a></li>
                <li><a href="javascript:;" class="list-button item-link close-popover" data-status="4">待评价({$count.evaluation_count|default=0})</a></li>
            </ul>
        </div>
    </div>
</div>
{/block}
{block name="js"}
<script type="text/html" id="listTpl">
    {%each orderList as order%}
    <li class="card">
        <div class="card-header">
            <span>订单号: {%order.order_sn%}</span>
            <span>{%order.status_text%}</span>
        </div>
        <div class="card-content">
            <a class="card-content-inner" href="javascript:;">
                <ul class="product-list">
                    {%each order.goods_list as goods%}
                    <li class="product-item flex">
                        <div class="product-img">
                            <img src="{%goods.cover_image%}" style="width: 4rem;height: 4rem;">
                        </div>
                        <div class="product-info flex-1">
                            <h4 class="product_name">{%goods.name%}</h4>
                            <p>
                                <span class="product_price price_com">{%goods.sell_price%}</span>
                                <span class="product_unit">/{%goods.unit%}</span>
                            </p>
                            {%if (goods.spec_str != '')%}
                                <p class="product_spec">{%goods.spec_str%}</p>
                            {%/if%}
                            <span class="product_num">x{%goods.buy_num%}</span>
                        </div>
                    </li>
                    {%/each%}
                </ul>
            </a>
        </div>
        <div class="card-footer no-border">
            <span>数量：{%order.goods_num%}</span>
            <p>总金额: <span class="price_com">{%order.pay_price%}</span></p>
        </div>
        <div class="card-footer">
            <span>下单时间: {%order.add_time%}</span>
            {%if (order.status==0)%}
                <a href="{:url('/payment/wechat/index')}?ordersn={%order.order_sn%}" class="button button-fill button-warning">去支付</a>
            {%/if%}
        </div>
    </li>
    {%/each%}
</script>
<script>
    $(function () {
        var order = {
            url: {
                get_order_list: '{:url("order/getOrderList")}',
            },
            page: 1,
            limit: parseInt('{$limit}'),
            pageNum: 1,
            keyword: '',
            status: 0,
            preloader: $('.infinite-scroll-preloader'),
            loaded_tip: $('.loaded-tip'),
            order_list_html: $('.order-list'),
            getList: function () {
                var instance = this;
                var params = {};
                params.page = this.page;
                if(this.status) params.status = this.status;
                if(this.keyword) params.keyword = this.keyword;
                this.loaded_tip.hide();
                $.post(this.url.get_order_list,params,function (result) {
                    instance.render(result.orderList);
                });
                return this;
            },
            setStatus: function (status) {
                this.status = status;
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
                this.order_list_html.html('');
                this.preloader.show();
                return this;
            },
            render: function (orderList) {
                var len = $.getObjLen(orderList);
                if(len > 0){
                    this.order_list_html.html(template('listTpl',{orderList:orderList}));
                }
                if(len < this.limit){
                    this.loaded_tip.show();
                    this.preloader.hide();
                }else{
                    this.page++;
                }
            }
        }.getList();

        $(document).on('infinite', '.infinite-scroll',function() {
            order.getList();
        });

        $('#filtrate').keydown(function (e) {
            if(e.keyCode == 13){
                var keyword = $(this).val().trim();
                order.reset().setKeyword(keyword).getList();
            }
        });

        $('.popover-order_status .list-button').click(function () {
            var text = $(this).text().trim();
            var title = $('#bar-title');
            var icon = title.find('span').clone();
            title.html(text).append(icon);
            order.reset().setStatus($(this).data('status')).getList();
        });
    });
</script>
{/block}