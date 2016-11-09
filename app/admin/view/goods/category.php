{extend name="layout/base" /}
{block name="css"}
    <link href="__CSS__/metroStyle/metroStyle.css" rel="stylesheet" >
{/block}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin">
                <div class="widget-header widget-fruiter">
                    <a class="btn btn-success" id="add" href="javascript:void(0);">添加</a>
                    <a id="save" class="btn btn-success" href="javascript:void(0);">保存</a>
                    <span class="division"> | </span>
                    <a id="delete" class="btn btn-danger" href="javascript:void(0);">删除</a>
                </div><!--Widget Header-->
                <div class="widget-body plugins_category-">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="well">
                                <table class="table table-bordered table-condensed table-middle flip-content dataTable">
                                    <thead class="flip-content bordered-palegreen">
                                    <tr>
                                        <th width="70%">分类名称</th>
                                        <th width="30%">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $show_category = function($category,$level,$path) use (&$show_category){
                                        if(!empty($category) && is_array($category)):
                                            $class = '';
                                            if($level){
                                                $class = '<span class="margin-left-' . 20 * $level . '"></span>';
                                            }
                                            if($level < 2){
                                                $icon = '<i class="fa row-details fa-minus-square-o ifold"></i>';
                                            }
                                            foreach($category as $value):
                                                $tmp_path = $path . $value['id'] . '_';
                                                $html = '<tr data-id="' . $value['id'] . '" data-path="' . $tmp_path . '" data-pid="' . $value['pid'] . '">';
                                                if(empty($value['child'])){
                                                    $icon = '<i class="fa row-details"></i>';
                                                }
                                                $icon_img = '';
                                                if(!empty($value['icon'])){
                                                    $icon_img = '<img class="icon" src="'.get_img_url($value['icon']).'" height="50px" width="50px"/>';;
                                                }
                                                $html .= '<td>' . $class . $icon . '&nbsp;' . $icon_img . '<span class="category_name">' .$value['name'] . '</span></td>
                                                        <td>
                                                            <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="up"><i class="fa fa-arrow-up"></i></a>
                                                            <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="down"><i class="fa fa-arrow-down"></i></a>
                                                            <a class="btn btn-success btn-xs shiny icon-only white btn-get" href="javascript:void(0);"><i class="fa fa-edit"></i></a>
                                                        </td>
                                                    </tr>';
                                                echo $html;
                                                if(!empty($value['child'])):
                                                    $show_category($value['child'],++$level,$tmp_path);
                                                endif;
                                            endforeach;
                                        endif;
                                    };$show_category($goodsCategories,0,'path_');?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="well">
                                <form action="{:url('goodsCategory/edit')}" id="form-edit" class="form-horizontal bv-form form-category" data-action="edit" onsubmit="return false;" autocomplete="off">
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">分类名称
                                            <span class="red">*</span>：
                                        </label>
                                        <div class="col-lg-8">
                                            <input name="name" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">分类图标：</label>
                                        <div class="col-lg-8">
                                            <div class="input-group input-group-sm">
                                                <input type="text" readonly="" name="icon" id="icon-edit" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default btn-success" onclick="BrowseServer('icon-edit');" type="button">选择图片</button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">上级分类
                                            <span class="red">*</span>：
                                        </label>
                                        <div class="col-lg-8">
                                            <input name="p_name" id="p_name" class="form-control" type="text" readonly>
                                            <input name="p_id" id="p_id" type="hidden"/>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">SEO标题：</label>
                                        <div class="col-lg-8">
                                            <input name="title" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">SEO关键词：</label>
                                        <div class="col-lg-8">
                                            <input name="keywords" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">SEO描述：</label>
                                        <div class="col-lg-8">
                                            <span class="input-icon icon-right">
                                                <textarea name="descript" class="form-control"></textarea>
                                                <i class="fa  fa-rocket darkorange"></i>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!--Widget Body-->
            </div><!--Widget-->
        </div>
    </div>
    <div id="addModal" style="display:none;">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal bv-form form-category" method="post" data-action="add" action="{:url('goodsCategory/add')}" autocomplete="off"></form>
            </div>
        </div>
    </div>
    <div id="tree_panel" class="tree_panel">
        <ul id="tree_category" class="ztree ztree_entity"></ul>
    </div>
{/block}
{block name="js"}
<script>
    $(function(){
        $('#add').click(function(){
            bootbox.dialog({
                message: function(){
                    var form = $('#addModal .col-md-12 .form-category');
                    if(form.html() == ''){
                        var html = $('form.form-category').html();
                        html = html.replace(/icon-edit/g,'icon-tmp');
                        form.append(html);
                    }
                    else{form[0].reset();}
                    form.find('.position-root').hide();
                    return $("#addModal").html().replace(/icon-tmp/g,'icon-add');
                },
                title: "添加分类",
                className: "modal-sky",
                buttons: {
                    "cancel": {
                        label: "取消",
                        className: "btn-default",
                        callback: function () { }
                    },
                    "confirm": {
                        label: "确定",
                        className: "btn-success",
                        callback: function () {
                            $('.modal-dialog form.form-category').submit();
                        }
                    }
                }
            });
        });
        $('#save').click(function(){
            if(category_id > 0){
                var query = $('.plugins_category- form').serialize();
                query += '&id=' + category_id;
                $.fruiter.post('{:url("goodsCategory/edit")}',{params:encodeURIComponent(query)},function(data){
                    if(data.code == 1){
                        var tr = $('.plugins_category- .dataTable').find('tr[data-id='+category_id+']');
                        tr.find('.category_name').text($('.plugins_category- form').find('input[name=name]').val());
                        if(data.icon_url){
                            if(tr.find('.icon').length){
                                tr.find('.icon').attr('src',data.icon_url);
                            }else{
                                tr.find('.category_name').before('<img class="icon" src="'+data.icon_url+'" width="50px" height="50px">');
                            }
                        }
                        Notify(data.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                    }else{
                        Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                    }
                });
            }else{
                Notify('请先选择分类', 'bottom-right', '5000', 'warning', 'fa-warning', true);
            }
        });
        $('#delete').click(function(){
            if(category_id > 0){
                var category_name = $('.plugins_category- .dataTable').find('tr[data-id='+category_id+']').find('.category_name').text();
                bootbox.confirm("确定要删除<span class='red'>"+category_name+"</span>分类?", function (result) {
                    if (result) {
                        $.fruiter.post('{:url("goodsCategory/del")}',{id:category_id},function(data){
                            if(data.code == 1){
                                $('.plugins_category- table').find('tr[data-id='+category_id+']').remove();
                                category_id = null;
                                document.getElementById('form-edit').reset();
                                Notify(data.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                            }else{
                                Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                            }
                        });
                    }
                });
            }else{
                Notify('请先选择分类', 'bottom-right', '5000', 'warning', 'fa-warning', true);
            }
        });
    });
</script>
<script src="__JS__/jquery.ztree.all-3.5.min.js"></script>
<script>
    //商品分类操作事件
    var setting = {
        check: {
            enable: true,
            chkStyle: "radio",
            radioType: 'all'
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

    var zNodes = JSON.parse('{$tree|json_encode}'),
        zTree = null,
        category_id = 0;

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

        zTree.formOjb.find('input[name=p_name]').val(name);
        zTree.formOjb.find('input[name=p_id]').val(id);
        zTree.formOjb.find('input[name=level]').val(treeNode.level);
    }

    function hideMenu() {
        $("#tree_panel").slideUp("fast");
        $("body").unbind("mousedown", onBodyDown);
    }

    function onBodyDown(event) {
        if (!(event.target.id == "p_name" || event.target.id == "tree_panel" || $(event.target).parents("#tree_panel").length>0)) {
            hideMenu();
        }
    }

    $(document).ready(function(){

        zTree = $.fn.zTree.init($("#tree_category"), setting, zNodes);

        $(this).on('click','input[name=p_name]',function(){
            var form = $(this).parents('form');
            var type = form.data('action');
            zTree.formOjb = form;
            var p_id = form.find('input[name=p_id]').val();
            if(p_id != ''){
                p_id = Number(p_id);
                var nodes = zTree.getNodes();
                for(var i in nodes){
                    if(nodes[i].id == p_id){
                        nodes[i].checked = 'checked';
                        zTree.updateNode(nodes[i],false);
                        break;
                    }
                }
            }

            $("#tree_panel").css({
                left:$(this).offset().left + "px",
                top:$(this).offset().top + $(this).outerHeight() - $('.navbar-inner').height() + "px",
                width: $(this).outerWidth() + "px"
            }).slideDown("fast");

            $("body").bind("mousedown", onBodyDown);
        });

        $('.plugins_category- table').find('.btn-get').click(function(){
            var tr = $(this).parents('tr');
            if(!$(tr).hasClass('tr-focus')){
                $(tr).parent().find('.tr-focus').removeClass('tr-focus');
                $(tr).addClass('tr-focus');
            }
            var form = document.getElementById('form-edit');
            $.fruiter.post("{:url('goodsCategory/getCategory')}",{id:$(tr).data('id')},function(data){
                if(data){
                    category_id = data.id;
                    form.name.value = data.name;
                    form.icon.value = data.icon;
                    form.p_id.value = data.pid;
                    form.title.value = data.title;
                    form.keywords.value = data.keywords;
                    form.descript.value = data.descript;
                    var nodes = zTree.getNodes();
                    for(var i in nodes){
                        if(data.pid == 0 || nodes[i].id == data.pid){
                            nodes[i].checked = 'checked';
                            zTree.updateNode(nodes[i],false);
                            form.p_name.value = nodes[i].name;
                            break;
                        }
                    }
                }
            });
        });

        $('.plugins_category- table').find('.btn-move').click(function(){
            var action = $(this).data('action');
            var tr = $(this).parents('tr');
            if(!$(tr).hasClass('tr-focus')){
                $(tr).parent().find('.tr-focus').removeClass('tr-focus');
                $(tr).addClass('tr-focus');
            }
            var pid = $(tr).data('pid');
            var trs = $(tr).parents('tbody').find('tr[data-pid='+pid+']');
            if(tr.data('id') == $(trs[0]).data('id') && action == 'up' ){
                Notify('无法上移', 'bottom-right', '5000', 'warning', 'fa-warning', true);
            }else if(tr.data('id') == $(trs[trs.length-1]).data('id') && action == 'down' ){
                Notify('无法下移', 'bottom-right', '5000', 'warning', 'fa-warning', true);
            }else{
                $.fruiter.post("{:url('goodsCategory/move')}",{id:$(tr).data('id'),action:action},function(data){
                    if(data.code == 1){
                        for(var i in trs){
                            if($(trs[i]).data('id') == tr.data('id')){
                                if(action == 'up'){
                                    $('tr[data-path^=' + tr.data('path') + ']').insertBefore($(trs[parseInt(i)-1]));
                                }else if(action == 'down'){
                                    $('tr[data-path^=' + tr.data('path') + ']').insertAfter($(trs[parseInt(i)+1]));
                                }
                                break;
                            }
                        }
                        Notify(data.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                    }else{
                        Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                    }
                });
            }
        });

        $('.ifold').click(function(){
            var tr = $(this).parents('tr');
            var path = tr.data('path');
            if($(this).hasClass('fa-minus-square-o')){
                $('.plugins_category- .dataTable').find('tr[data-path^='+path+']').each(function(index){
                    if(index) $(this).hide();
                });
                $(this).removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
            }else if($(this).hasClass('fa-plus-square-o')){
                $('.plugins_category- .dataTable').find('tr[data-path^='+path+']').each(function(index){
                    if(index) $(this).show();
                });
                $(this).removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
            }
        });
    });
</script>
{/block}