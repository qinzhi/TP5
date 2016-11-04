{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-goods" id="page-evaluate_index">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="javascript:history.go(-1);">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">商品评价</h1>
    </header>
    <div class="content infinite-scroll">
        <div class="buttons-tab">
            <a href="#tab-all" class="tab-link active button" data-type="0">全部(3)</a>
            <a href="#tab-good" class="tab-link button" data-type="1">好评(3)</a>
            <a href="#tab-middle" class="tab-link button" data-type="2">中评(0)</a>
            <a href="#tab-bad" class="tab-link button" data-type="3">差评(0)</a>
        </div>
        <div class="tabs">
            <div id="tab-all" class="tab active"><ul class="evaluate_list"></ul><div class="infinite-scroll-preloader"><div class="preloader"></div></div></div>
            <div id="tab-good" class="tab"><ul class="evaluate_list"></ul><div class="infinite-scroll-preloader"><div class="preloader"></div></div></div>
            <div id="tab-middle" class="tab"><ul class="evaluate_list"></ul><div class="infinite-scroll-preloader"><div class="preloader"></div></div></div>
            <div id="tab-bad" class="tab"><ul class="evaluate_list"></ul><div class="infinite-scroll-preloader"><div class="preloader"></div></div></div>
        </div>
    </div>
</div>
{/block}
{block name="js"}
<script type="text/html" id="listTpl">
    {%each evaluateList as evaluate%}
        <li class="evaluate_item">
            <h5>
                <img class="avator" src="http://wx.qlogo.cn/mmopen/Q3auHgzwzM6dkNLaHQBND38jBNUPmib8dxd6z6Pvh6DRrUks3AgmrmqSibv1RFMEQiaJ12p09M92JQt8zf14dwH4CZgEEo3eSXHxxmuhAEWPJU/64"/> 啊秦智
                <p class="evaluate_time pull-right">2016-11-02</p>
            </h5>
            <p class="evaluate_content">苹果压坏了5个</p>
        </li>
    {%/each%}
</script>
<script>
    $(document).on("pageInit", "#page-evaluate_index", function(e,id,page) {

        var preloader = $('.infinite-scroll-preloader');

        var tab_all = $('.buttons-tab .button').eq(0);
        load_data(tab_all);


        function load_data (tab,infinite){
            var infinite = infinite || false;
            if(!tab.data('init')){
                tab.attr('data-init',1);
                tab.attr('data-page',1);
                tab.attr('data-page_num',-1);
            }else if(infinite != true){
                return;
            }
            var page = parseInt(tab.data('page'));
            var pageNum = parseInt(tab.data('page_num'));
            var type = parseInt(tab.data('type'));
            var tabIndex = tab.index();
            if(pageNum == -1 || page <= pageNum){
                set_data(tab,page,type);
            }else
                preloader.eq(tabIndex).hide();
        }

        function set_data(tab,page,type){
            var loading = parseInt(tab.data('loading'));
            if(loading) return;
            else tab.data('loading',1);

            $.post("{:url('evaluate/evaluateList')}",{page:page,type:type},function(result){
                tab.data('loading',0);
                var pageNum = result.pageNum;
                var tabIndex = tab.index();
                if(page >= pageNum || pageNum == 0){
                    preloader.eq(tabIndex).hide();
                }
                var evaluateList = result.data;
                tab.data('page_num',pageNum);
                if(evaluateList.length > 0){
                    tab.data('page',parseInt(page) + 1);
                    var html = template('listTpl',{evaluateList:evaluateList});
                    var evaluate_list = $(tab.attr('href')).find('.evaluate_list');
                    evaluate_list.append(html);
                }
            });
        }

        $(page).find(".infinite-scroll").on('infinite', function(e) {
            var tab = $($('.buttons-tab .button.active'));
            load_data(tab,true);
        });

        $('.buttons-tab .button').click(function(){
            load_data($(this));
        });
    });
</script>
{/block}