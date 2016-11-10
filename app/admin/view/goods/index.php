{extend name="layout/base" /}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin plugins_goods-">
                <div class="widget-header widget-fruiter">
                    <form class="pull-left goods_list_top" method="get">
                        <select class="input-sm Lwidth120" name="search[status]">
                            <option value="">选择上下架</option>
                            <option value="1">上架</option>
                            <option value="0">下架</option>
                        </select>
                        <select class="input-sm Lwidth120" name="search[store_nums]">
                            <option value="">选择库存</option>
                            <option value="go.store_nums < 1">无货</option>
                            <option value="go.store_nums >= 1 and go.store_nums < 10">低于10</option>
                            <option value="go.store_nums <= 100 and go.store_nums >= 10">10-100</option>
                            <option value="go.store_nums >= 100">100以上</option>
                        </select>
                        <select class="input-sm Lwidth120" name="search[commend_id]">
                            <option value="">选择商品类型</option>
                            <option value="1">最新商品</option>
                            <option value="2">特价商品</option>
                            <option value="3">热卖商品</option>
                            <option value="4">推荐商品</option>
                        </select>
                        <input id="category" type="text" class="input-sm Lwidth200" placeholder="选择商品分类" readonly>
                        <input id="category_id" name="search[category_id]" data-value='{$categories_id}' type="hidden">
                        <input name="search[keywords]" type="text" class="input-sm" placeholder="商品名或商品货号"/>
                        <button class="btn btn-success" id="search" type="submit">搜索</button>
                    </form>
                    <div class="pull-right">
                        <a class="btn btn-success" href="{:url('goods/add')}">添加商品</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body no-padding">
                    <table class="table table-hover table-middle">
                        <colgroup>
                            <col width="60px">
                            <col width="360px">
                            <col width="180px">
                            <col width="80px">
                            <col width="80px">
                            <col width="120px">
                            <col width="70px">
                            <col>
                        </colgroup>
                        <thead class="bordered-success">
                            <tr role="row">
                                <th class="padding-left-16">选择</th>
                                <th>商品名称</th>
                                <th>分类</th>
                                <th>销售价</th>
                                <th>库存</th>
                                <th>状态</th>
                                <th>排序</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            {volist name="goods" id="vo"}
                                <tr>
                                    <td class="padding-left-16">
                                        <div class="checkbox checkbox-inline no-margin no-padding">
                                            <label class="no-padding">
                                                <input type="checkbox" class="goods_id" name="id[]" value="{$vo.id}" autocomplete="off">
                                                <span class="text"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="input-edit" data-field="name" title="点击更新商品名称">{$vo.name}</td>
                                    <td class="goods-category" title="点击设置分类">手机</td>
                                    <td><a href="javascript:;" class="goods-price" title="点击更新价格">{$vo.sell_price}</a></td>
                                    <td><a href="javascript:;" class="goods-sku" title="点击更新库存">{$vo.store_nums}</a></td>
                                    <td>
                                        <label class="list-status goods-status">
                                            <input class="checkbox-slider toggle colored-success" type="checkbox" autocomplete="off" {$vo['status']?'checked':''}>
                                            <span class="text" title="设置商品上下架"></span>
                                        </label>
                                    </td>
                                    <td class="input-edit" data-field="sort" title="点击更新商品排序">{$vo.sort}</td>
                                    <td>
                                        <a class="btn btn-default btn-sm purple btn-edit" href="{:url('goods/edit',['id'=>$vo['id']])}" title="编辑">
                                            <i class="fa fa-edit"></i> 编辑</a>
                                        <a class="btn btn-default btn-sm danger btn-del" href="javascript:void(0);" title="删除">
                                            <i class="fa fa-times"></i> 删除</a>
                                    </td>
                                </tr>
                            {/volist}
                        </tbody>
                    </table>
                    <div class="row DTTTFooter padding-left-16">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="searchable_info" role="alert" aria-live="polite"
                                 aria-relevant="all">当前第1页/共1页
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_bootstrap" id="searchable_paginate">
                                {$goods->render()}
                            </div>
                        </div>
                    </div>
                </div><!--Widget Body-->
            </div><!--Widget-->
        </div>
    </div>
{/block}
{block name="js"}
    <script type="application/javascript">
        $(function () {
            var _goods = {
                update: function (params) {
                    $.fruiter.post('{:url("goods/update")}', params, function (data) {
                        if (data.code == 1) {
                            Notify(data.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                        } else {
                            Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                        }
                    });
                }
            }
            $('.input-edit').click(function () {
                if ($(this).find('input').length <= 0) {
                    var obj = $(this);
                    var input = $('<input type="text" class="input-xs form-control"/>');
                    var val = $(this).text();
                    input.val(val).data('value', val);
                    $(this).html(input);
                    var field = $(this).data('field');
                    input.focus().select();
                    input.bind('blur', function () {
                        var cur_val = $(this).val();
                        var val = $(this).data('value');
                        if (!!cur_val && cur_val != val) {
                            var params = {};
                            params.id = $(this).closest('tr').find('.goods_id').val();
                            params[field] = cur_val;
                            _goods.update(params);
                            obj.text(cur_val);
                        } else {
                            obj.text(val);
                        }
                    });
                }
            });
            $('.goods-status input[type=checkbox]').change(function () {
                var params = {};
                params.id = $(this).closest('tr').find('.goods_id').val();
                params.status = this.checked ? 1 : 0;
                _goods.update(params);
            });
            $('.goods-price').click(function () {
                var obj = $(this);
                var goods_id = $(this).closest('tr').find('.goods_id').val();
                $.dialog({
                    id : 'setPrice',
                    title : '设置商品价格',
                    async : false,
                    min_width: 600,
                    min_height: 350,
                    content : function(){
                        var content;
                        $.post("{:url('goods/product')}",{tpl:'price',id:goods_id},function(data){
                            content = data;
                        });
                        return content;
                    },
                    ok : function(target){
                        var $return = true;
                        target.find('input[type=text]:visible').each(function(){
                            if($.validateOnChange(this) === false){
                                $return = false;
                            }
                        });
                        if($return){
                            var params = target.find('form').serialize();
                            $.post("{:url('goods/update')}",params,function(result){
                                if(result.code == 1){
                                    var _default = target.find('input[name=_default]');
                                    var sell_price = _default.closest('tr').find('input[name="_sell_price[]"]').val();
                                    obj.text(sell_price);
                                    Notify(result.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                                }else{
                                    Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                                }
                            });
                        }else
                            return $return;
                    }
                });
            });
            $('.goods-sku').click(function () {
                var obj = $(this);
                var goods_id = $(this).closest('tr').find('.goods_id').val();
                $.dialog({
                    id : 'setSku',
                    title : '设置商品库存',
                    async : false,
                    min_width: 400,
                    min_height: 250,
                    content : function(){
                        var content;
                        $.post("{:url('goods/product')}",{tpl:'sku',id:goods_id},function(data){
                            content = data;
                        });
                        return content;
                    },
                    ok : function(target){
                        var $return = true;
                        target.find('input[type=text]:visible').each(function(){
                            if($.validateOnChange(this) === false){
                                $return = false;
                            }
                        });
                        if($return){
                            var params = target.find('form').serialize();
                            $.post("{:url('goods/update')}",params,function(result){
                                if(result.code == 1){
                                    var store_nums = target.find('input[name="_store_nums[]"]');
                                    var total = 0;
                                    $.each(store_nums,function(){
                                        total += parseInt(this.value);
                                    });
                                    obj.text(total);
                                    Notify(result.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                                }else{
                                    Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                                }
                            });
                        }else
                            return $return;
                    }
                });
            });
            $('.btn-del').click(function(){
                var goods_id = $(this).closest('tr').find('.goods_id').val();
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        $.post('{:url("goods/del")}',{id:goods_id},function(result){
                            if(result.code == 1){
                                window.location.reload();
                            }else{
                                Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                            }
                        });
                    }
                });
            });
        });
    </script>
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
                top:$(this).offset().top + $(this).outerHeight()/* - $('.navbar-inner').height()*/ + "px",
                width: $(this).outerWidth() + "px"
            }).slideDown("fast");

            if($('#tree_category').find('li').length < 1){
                $('#tree_panel').append('<p style="position: absolute;left:5px;top:5px;">数据获取中...</p>');
                $.post('{:url("goodsCategory/getCategoriesTree")}',function(tree){
                    $('#tree_panel').find('p').remove();
                    zTree = $.fn.zTree.init($("#tree_category"), setting, tree);
                });
            }
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

        $(function(){
            create_category_panel();
        });
    </script>
{/block}
{block name="css"}
    <link href="__CSS__/metroStyle/metroStyle.css" rel="stylesheet" >
{/block}