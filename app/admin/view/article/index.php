{extend name="layout/base" /}
{block name="css"}
    <link href="__CSS__/metroStyle/metroStyle.css" rel="stylesheet" >
{/block}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin plugins_article-">
                <div class="widget-header widget-fruiter">
                    <form class="pull-left goods_list_top" method="get">
                        <input id="category" type="text" class="input-sm Lwidth200" placeholder="选择文章分类" readonly>
                        <input id="category_id" class="Lwidth300" name="search[category_id]" data-value='{$categories_id}' type="hidden">
                        <input name="search[keywords]" type="text" class="input-sm" placeholder="文章标题"/>
                        <button class="btn btn-success" id="search" type="submit">搜索</button>
                    </form>
                    <div class="pull-right">
                        <a class="btn btn-success" href="{:url('article/add')}">添加文章</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body no-padding">
                    <table class="table table-hover table-middle">
                        <colgroup>
                            <col width="60px">
                            <col width="360px">
                            <col width="180px">
                            <col width="150px">
                            <col width="80px">
                            <col>
                        </colgroup>
                        <thead class="bordered-success">
                        <tr role="row">
                            <th class="padding-left-16">选择</th>
                            <th>标题</th>
                            <th>文章分类</th>
                            <th>发布时间</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                            {volist name="articles" id="vo"}
                                <tr>
                                    <td class="padding-left-16">
                                        <div class="checkbox checkbox-inline no-margin no-padding">
                                            <label class="no-padding">
                                                <input type="checkbox" class="article_id" name="id[]" value="{$vo.id}" autocomplete="off">
                                                <span class="text"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="input-edit" data-field="name" title="点击更新标题">{$vo.title}</td>
                                    <td class="article-category" title="点击设置分类" data-category_id="{$vo.category_id}">{$vo.category}</td>
                                    <td>{$vo.create_time|date='Y-m-d H:i:s',###}</td>
                                    <td class="input-edit" data-field="sort" title="点击更新商品排序">{$vo.sort}</td>
                                    <td>
                                        <a class="btn btn-default btn-sm purple btn-edit" href="{:url('article/edit',array('id'=>$vo['id']))}" title="编辑">
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
                                <ul class="pagination">
                                    <li class="prev disabled"><a href="#">上一页</a></li>
                                    <li class="active"><a href="#">1</a></li>
                                    <li class="next disabled"><a href="#">下一页</a></li>
                                </ul>
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
            var _article = {
                update: function (params) {
                    $.fruiter.post('{:url("article/update")}', params, function (data) {
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
                            params.id = $(this).closest('tr').find('.article_id').val();
                            params[field] = cur_val;
                            _article.update(params);
                            obj.text(cur_val);
                        } else {
                            obj.text(val);
                        }
                    });
                }
            });
            $('.btn-del').click(function(){
                var article_id = $(this).closest('tr').find('.article_id').val();
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        $.post('{:url("article/del")}',{id:article_id},function(result){
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
                $.post('{:url("articleCategory/getCategoriesTree")}',function(tree){
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