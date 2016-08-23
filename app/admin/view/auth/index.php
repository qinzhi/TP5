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
                    <span> | </span>
                    <a id="delete" class="btn btn-danger" href="javascript:void(0);">删除</a>
                </div><!--Widget Header-->
                <div class="widget-body plugins_auth-">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="well">
                                <table class="table table-bordered table-condensed table-middle table-auth_">
                                    <thead class="flip-content bordered-palegreen">
                                    <tr>
                                        <th width="40%">模块名称</th>
                                        <th width="40%">地址</th>
                                        <th width="20%">操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $show_auth = function($auth,$level,$path) use (&$show_auth){
                                            if(!empty($auth) && is_array($auth)):
                                                $class = '';
                                                if($level){
                                                    $class = '<span class="margin-left-' . 20 * $level . '"></span>';
                                                }
                                                if($level < 2){
                                                    $icon = '<i class="fa row-details fa-minus-square-o ifold"></i>';
                                                }
                                                foreach($auth as $value):
                                                    $tmp_path = $path . $value['id'] . '_';
                                                    $html = '<tr data-id="' . $value['id'] . '" data-path="' . $tmp_path . '" data-pid="' . $value['pid'] . '">';
                                                    if(empty($value['child'])){
                                                        $icon = '';
                                                    }
                                                    $html .= '<td>' . $class . $icon . '&nbsp;<span class="authName">' .$value['name'] . '</span></td>
                                                        <td>' . $value['site'] . '</td>
                                                        <td>
                                                            <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="up"><i class="fa fa-arrow-up"></i></a>
                                                            <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="down"><i class="fa fa-arrow-down"></i></a>
                                                            <a class="btn btn-success btn-xs shiny icon-only white btn-get" href="javascript:void(0);"><i class="fa fa-edit"></i></a>
                                                        </td>
                                                    </tr>';
                                                    echo $html;
                                                    if(!empty($value['child'])):
                                                        $show_auth($value['child'],$level+1,$tmp_path);
                                                    endif;
                                                endforeach;
                                            endif;
                                        };$show_auth($auth,0,'path_');?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6">
                            <div class="well">
                                <form action="{:url('auth/edit')}" id="form-edit" class="form-horizontal bv-form form-auth" data-action="edit" onsubmit="return false;" autocomplete="off">
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">模块名称
                                            <span class="red">*</span>：
                                        </label>
                                        <div class="col-lg-8">
                                            <input name="name" class="form-control input-sm" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">父节点
                                            <span class="red">*</span>：
                                        </label>
                                        <div class="col-lg-8">
                                            <input name="p_name" id="p_name" class="form-control input-sm" type="text" readonly>
                                            <input name="p_id" id="p_id" type="hidden"/>
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">地址
                                            <span class="red">*</span>：
                                        </label>
                                        <div class="col-lg-8">
                                            <input name="site" class="form-control input-sm" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label class="col-lg-4 control-label">类型：</label>
                                        <div class="col-lg-8">
                                            <select name="type" class="form-control input-sm">
                                                <option value="1">URL</option>
                                                <option value="2">菜单</option>
                                            </select>
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
                <form class="form-horizontal bv-form form-auth" method="post" data-action="add" action="{:url('auth/add')}" autocomplete="off"></form>
            </div>
        </div>
    </div>
    <div id="tree_panel" class="tree_panel">
        <ul id="tree_auth" class="ztree ztree_entity"></ul>
    </div>
{/block}
{block name="js"}
    <script>
        $(function(){
           $('#add').click(function(){
               bootbox.dialog({
                   message: function(){
                       var form = $('#addModal .col-md-12 .form-auth');
                       if(form.html() == ''){
                           form.append($('form.form-auth').html());
                       }
                       else{form[0].reset();}
                       form.find('.position-root').hide();
                       return $("#addModal").html();
                   },
                   title: "添加权限",
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
                               $('.modal-dialog form.form-auth').submit();
                           }
                       }
                   }
               });
           });
            $('#save').click(function(){
                if(auth_id > 0){
                    var form = $('.plugins_auth- form');
                    var query = form.serialize();
                    query += '&id=' + auth_id;
                    $.fruiter.post('{:url("auth/edit")}',{params:encodeURIComponent(query)},function(data){
                        if(data.code == 1){
                            var tr = $('.table-auth_').find('tr[data-id="'+auth_id+'"]');
                            tr.find('td:eq(0)').find('.authName').text(form.find('input[name=name]').val());
                            tr.find('td:eq(1)').text(form.find('input[name=site]').val());
                            Notify(data.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                        }else{
                            Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                        }
                    });
                }else{
                    Notify('请先选择权限', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                }
            });
            $('#delete').click(function(){
                if(auth_id > 0){
                    $.fruiter.post('{:url("auth/del")}',{id:auth_id},function(data){
                        if(data.code == 1){
                            $('.plugins_auth- table').find('tr[data-id='+auth_id+']').remove();
                            auth_id = null;
                            document.getElementById('form-edit').reset();
                            Notify(data.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                        }else{
                            Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                        }
                    });
                }else{
                    Notify('请先选择权限', 'bottom-right', '5000', 'warning', 'fa-warning', true);
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
            auth_id = 0;

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

            zTree = $.fn.zTree.init($("#tree_auth"), setting, zNodes);

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

            $('.plugins_auth- table').find('.btn-get').click(function(){
                var tr = $(this).parents('tr');
                if(!$(tr).hasClass('tr-focus')){
                    $(tr).parent().find('.tr-focus').removeClass('tr-focus');
                    $(tr).addClass('tr-focus');
                }
                var form = document.getElementById('form-edit');
                $.fruiter.post("{:url('auth/getAuth')}",{id:$(tr).data('id')},function(data){
                    if(data){
                        auth_id = data.id;
                        form.name.value = data.name;
                        form.site.value = data.site;
                        $(form.type).find('option[value='+data.type+']').attr('selected',true);
                        form.p_id.value = data.pid;
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

            $('.plugins_auth- table').find('.btn-move').click(function(){
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
                    $.fruiter.post("{:url('auth/move')}",{id:$(tr).data('id'),action:action},function(data){
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
                    $('.table-auth_').find('tr[data-path^='+path+']').each(function(index){
                        if(index) $(this).hide();
                    });
                }else if($(this).hasClass('fa-plus-square-o')){
                    $('.table-auth_').find('tr[data-path^='+path+']').each(function(index){
                        if(index) $(this).show();
                    });
                }
                $(this).toggleClass('fa-plus-square-o').toggleClass('fa-minus-square-o');
            });
        });
    </script>
{/block}