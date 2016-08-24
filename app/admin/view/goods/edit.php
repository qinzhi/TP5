{extend name="layout/base" /}
{block name="css"}
    <link href="__CSS__/metroStyle/metroStyle.css" rel="stylesheet" >
{/block}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin">
                <div class="widget-header widget-fruiter">
                    <div class="pull-right">
                        <a class="btn btn-success" id="goods_save" href="javascript:void(0);">发布商品</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body plugins_goods-">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form class="tabbable" id="goodsForm" method="post" autocomplete="off">
                                <ul class="nav nav-tabs tabs-flat">
                                    <li class="active">
                                        <a href="#tab-basic" data-toggle="tab">
                                            商品信息
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab-detail" data-toggle="tab">
                                            商品描述
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab-attr" data-toggle="tab">
                                            商品属性
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab-seo" data-toggle="tab">
                                            商品SEO
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content tabs-flat">
                                    <div class="tab-pane active" id="tab-basic">
                                        {include file="goods/edit/base"}
                                    </div>
                                    <div class="tab-pane" id="tab-detail">
                                        {include file="goods/edit/detail"}
                                    </div>
                                    <div class="tab-pane" id="tab-attr">
                                        {include file="goods/edit/attr"}
                                    </div>
                                    <div class="tab-pane" id="tab-seo">
                                        {include file="goods/edit/seo"}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!--Widget Body-->
            </div><!--Widget-->
        </div>
    </div>
{/block}
{block name="js"}
    <script src="__JS__/jquery.ztree.all-3.5.min.js"></script>
    <script>
        //商品分类操作事件
        var setting = {
            check: {
                enable: true,
                chkboxType: {"Y":"p", "N":"p"},
                nocheckInherit: true
            },
            view: {
                dblClickExpand: false
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            callback: {
                beforeClick: beforeClick,
                onCheck: onCheck
            }
        };

        var zTree = null;

        function beforeClick(treeId, treeNode) {
            zTree.checkNode(treeNode, !treeNode.checked, null, true);
            return false;
        }

        function onCheck(e, treeId, treeNode) {
            var nodes = zTree.getCheckedNodes(true),
                name = [],id=[];
            for (var i in nodes) {
                name.push(nodes[i].name);
                id.push(nodes[i].id);
            }

            if (name.length > 0 ) name = name.join();
            if (id.length > 0 ) id = id.join();

            $("#category").attr("value", name);
            $("#category_id").attr("value", id);

        }
        $('#category').click(function(){
            $("#tree_panel").css({
                left:$(this).offset().left + "px",
                top:$(this).offset().top + $(this).outerHeight()/* - $('.navbar-inner').height() */+ "px",
                width: $(this).outerWidth() + "px"
            }).slideDown("fast");

            $("body").bind("mousedown", onBodyDown);
        }) ;

        function hideMenu() {
            $("#tree_panel").slideUp("fast");
            $("body").unbind("mousedown", onBodyDown);
        }

        function onBodyDown(event) {
            if (!(event.target.id == "category" || event.target.id == "tree_panel" || $(event.target).parents("#tree_panel").length>0)) {
                hideMenu();
            }
        }

        $(document).ready(function(){

            create_category_panel();

            $.post('{:url("goodsCategory/getCategoriesTree")}',function(tree){
                var categories_id = $('#category_id').data('value'),categories = [];
                $('#category_id').val(categories_id.join(','));
                for(var i in tree){
                    if(categories_id.indexOf(tree[i].id) != -1){
                        tree[i].checked = true;
                        categories.push(tree[i].name);
                    }
                }
                $('#category').val(categories.join(','));
                zTree = $.fn.zTree.init($("#tree_category"), setting, tree);
            });

            $('#goods_save').click(function(){
                var form = document.getElementById('goodsForm');
                if($.validateOnSubmit(form) == true){
                    form.submit();
                }
            });

            $(this).on('blur','input',function(){
                if($.validateOnChange(this) === true){
                    if($(this).parent().hasClass('has-error')){
                        $(this).parent().removeClass('has-error');
                    }
                }else{
                    if(!$(this).parent().hasClass('has-error')){
                        $(this).parent().addClass('has-error');
                    }
                }
            });
        });
    </script>
{/block}