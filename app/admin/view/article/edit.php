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
                        <a class="btn btn-success" id="article_save" href="javascript:void(0);">保存</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body plugins_goods-">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form class="tabbable" id="articleForm" method="post" autocomplete="off">
                                <ul class="nav nav-tabs tabs-flat">
                                    <li class="active">
                                        <a href="#tab-basic" data-toggle="tab">
                                            文章信息
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab-detail" data-toggle="tab">
                                            文章详情
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content tabs-flat">
                                    <div class="tab-pane active" id="tab-basic">
                                        <table class="table-form">
                                            <colgroup>
                                                <col width="150px"><col>
                                            </colgroup>
                                            <tbody>
                                            <tr>
                                                <th>文章标题：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <input id="title" name="title" class="input-sm Lwidth400" value="{$article.title}" type="text" pattern="required" maxlength="50">
                                                        <span class="note control-label margin-left-10">*</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>文章副标题：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <input id="subtitle" name="subtitle" class="input-sm Lwidth400" value="{$article.subtitle}" type="text" maxlength="50">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>所属分类：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <input id="category" class="input-sm Lwidth400" value="{$article.category}" type="text">
                                                        <input id="category_id" name="category_id" value="{$article.category_id}" class="hidden" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>排序：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <div class="spinner Lwidth400">
                                                            <input type="text" name="sort" class="spinner-input form-control" value="{$article.sort}">
                                                            <div class="spinner-buttons	btn-group btn-group-vertical">
                                                                <button type="button" class="btn spinner-up blue">
                                                                    <i class="fa fa-angle-up"></i>
                                                                </button>
                                                                <button type="button" class="btn spinner-down darkorange">
                                                                    <i class="fa fa-angle-down"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>SEO关键词：</th>
                                                <td><input id="keywords" name="keywords" value="{$article.keywords}" class="input-sm Lwidth400" type="text"></td>
                                            </tr>
                                            <tr>
                                                <th>SEO描述：</th>
                                                <td>
                                                        <span class="input-icon icon-right Lwidth400">
                                                            <textarea name="description" class="form-control">{$article.description}</textarea>
                                                            <i class="fa fa-rocket darkorange"></i>
                                                        </span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="tab-detail">
                                        <table class="table-form" width="100%">
                                            <colgroup>
                                                <col width="150px">
                                                <col>
                                            </colgroup>
                                            <tbody>
                                            <tr>
                                                <th>文章详情：</th>
                                                <td class="no-padding-top no-padding-bottom"><?php create_editor('detail',$article['detail']);?></td>
                                            </tr>
                                            </tbody>
                                        </table>
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
                chkStyle: "radio",
                radioType : 'all'
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

            $.post('{:url("articleCategory/getCategoriesTree")}',function(tree){
                zTree = $.fn.zTree.init($("#tree_category"), setting, tree);
            });

            $('#article_save').click(function(){
                var form = document.getElementById('articleForm');
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

            $('.spinner-up').click(function(){
                var input = $(this).closest('.spinner').find('input').get(0);
                input.value = parseInt(input.value) + 1;
            });
            $('.spinner-down').click(function(){
                var input = $(this).closest('.spinner').find('input').get(0);
                var val = parseInt(input.value);
                if(val > 0){
                    input.value = val - 1;
                }


            });
        });
    </script>
{/block}