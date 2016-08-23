{extend name="layout/base" /}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin">
                <div class="widget-header widget-fruiter">
                    <div class="pull-left">
                        <a class="btn btn-success" id="selectAll" data-status="false">全选</a>
                        <a id="batDel" class="btn btn-danger" href="javascript:void(0);">批量删除</a>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-success" id="addSpec" href="javascript:void(0);">添加规格</a>
                    </div>
                </div>
                <div class="widget-body plugins_spec- no-padding">
                    <table class="table table-hover">
                        <colgroup>
                            <col width="80px">
                            <col width="180px">
                            <col>
                            <col width="180px">
                        </colgroup>
                        <thead class="bordered-success">
                            <tr>
                                <th class="padding-left-16">选择</th>
                                <th>规格名称</th>
                                <th>规格数据</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody class="spec_list">
                            {volist name="specs" id="vo"}
                                <tr>
                                    <td class="padding-left-16">
                                        <div class="checkbox checkbox-inline no-margin no-padding">
                                            <label class="no-padding">
                                                <input type="checkbox" name="id[]" value="{$vo.id}" autocomplete="off">
                                                <span class="text"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>{$vo.name}</td>
                                    <td>{$vo.value|json_decode|implode=',',###}</td>
                                    <td>
                                        <a href="javascript:void(0);" data-node="{$vo.id}" class="btn btn-default btn-sm purple btn-edit"><i class="fa fa-edit"></i> 编辑</a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-sm danger btn-del"><i class="fa fa-times"></i> 删除</a>
                                    </td>
                                </tr>
                            {/volist}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name="js"}
    <script type="text/html" id="spec_list">
        <tr>
            <td class="padding-left-16">
                <div class="checkbox checkbox-inline no-margin no-padding">
                    <label class="no-padding">
                        <input type="checkbox" name="id[]" value="{{id}}" autocomplete="off">
                        <span class="text"></span>
                    </label>
                </div>
            </td>
            <td>{%name%}</td>
            <td>{%value | valueFormat%}</td>
            <td>
                <a href="{:url('goodsAttr/edit',['id'=>$vo['id']])}" class="btn btn-default btn-sm purple"><i class="fa fa-edit"></i> 编辑</a>
                <a href="javascript:void(0);" class="btn btn-default btn-sm danger btn-del"><i class="fa fa-times"></i> 删除</a>
            </td>
        </tr>
    </script>
    <script>
        $(document).ready(function(){
            $('#addSpec').click(function(){
                $.dialog({
                    id : 'addSpec',
                    title : '添加新规格',
                    async : false,
                    min_width: 600,
                    min_height: 350,
                    content : function(){
                        var content;
                        $.post("{:url('goods/spec')}",{tpl:'add'},function(data){
                            content = data;
                        });
                        return content;
                    },
                    ok : function(target){
                        var status = true;
                        var params = {};
                        params.name = $.trim($('#spec_name').val());
                        if(params.name == ''){
                            Notify('规格名称不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                            return false;
                        }
                        params.remark = $.trim($('#spec_show').val());
                        params.value = [];
                        $(target).find('table .spec_value').each(function(index){
                            params.value[index] = $(this).val();
                        });
                        $.fruiter.post("{:url('goodsSpec/add')}",params,function(result){
                            if(result.code == 1){
                                var data = result.data;
                                template.helper('valueFormat',function(value){
                                    return JSON.parse(value).join(',');
                                });
                                $('.spec_list').append(template('spec_list',data));
                                Notify(result.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                            }else{
                                Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                                status = false;
                            }
                        });
                        return status;
                    }
                });
            });
            var del = function(ids,rowsObj){
                $.fruiter.post("{:url('goodsSpec/del')}",{ids:ids},function(data){
                    if(data.code == 1){
                        $(rowsObj).each(function(){
                            $(this).remove();
                        });
                    }else{
                        Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                    }
                });
            }
            $(document).on('click','.plugins_spec- .btn-del',function(){
                var tr = $(this).closest('tr');
                var id = tr.find('input[name="id[]"]').val();
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        del(id,tr);
                    }
                });
            });
            $(document).on('click','.plugins_spec- .btn-edit',function(){
                var id = $(this).data('node');
                var tr = $(this).closest('tr');
                $.dialog({
                    id : 'editSpec',
                    title : '添加新规格',
                    async : false,
                    min_width: 600,
                    min_height: 350,
                    content : function(){
                        var content;
                        $.post("{:url('goods/spec')}",{tpl:'edit',id:id},function(data){
                            content = data;
                        });
                        return content;
                    },
                    ok : function(target){
                        var status = true;
                        var params = {};
                        params.id = id;
                        params.name = $.trim($('#spec_name').val());
                        if(params.name == ''){
                            Notify('规格名称不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                            return false;
                        }
                        params.remark = $.trim($('#spec_show').val());
                        params.value = [];
                        $(target).find('table .spec_value').each(function(index){
                            params.value[index] = $(this).val();
                        });
                        $.fruiter.post("{:url('goodsSpec/edit')}",params,function(result){
                            if(result.code == 1){
                                tr.find('td:eq(1)').text(params.name);
                                tr.find('td:eq(2)').text(params.value.join(','));
                                Notify(result.msg, 'bottom-right', '5000', 'success', 'fa-check', true);
                            }else{
                                Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                                status = false;
                            }
                        });
                        return status;
                    }
                });
            });
            $('#batDel').click(function(){
                var checkbox = $('.widget-body').find('input[name="id[]"]:checked');
                if(checkbox.length > 0){
                    bootbox.confirm("确定要批量删除么?", function (result) {
                        if(result){
                            var ids = '',rowsOjb = [];
                            checkbox.each(function(){
                                ids += ',' + this.value;
                                rowsOjb.push($(this).closest('tr'));
                            });
                            del(ids.substr(1),rowsOjb);
                        }
                    });
                }else{
                    Notify('请至少选择一项规格', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                }
            });
            $('#selectAll').click(function(){
                var status = $(this).data('status');
                if(status){
                    $(this).text('全选');
                    $('.widget-body').find('input[name="id[]"]').removeAttr('checked');
                }else{
                    $(this).text('反选');
                    $('.widget-body').find('input[name="id[]"]').attr('checked',true);
                }
                $(this).data('status',!status);
            });
        });
    </script>
{/block}