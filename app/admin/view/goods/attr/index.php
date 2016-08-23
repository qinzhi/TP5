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
                        <a class="btn btn-success" href="{:url('goodsAttr/add')}">添加模型</a>
                    </div>
                </div><!--Widget Header-->

                <div class="widget-body plugins_attr- no-padding">
                    <table class="table table-hover">
                        <colgroup>
                            <col width="80px">
                            <col>
                            <col width="180px">
                        </colgroup>
                        <thead class="bordered-success">
                            <tr>
                                <th class="padding-left-16">
                                    选择
                                </th>
                                <th>
                                    模型名称
                                </th>
                                <th>
                                    操作
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {volist name="models" id="vo"}
                                <tr>
                                    <td class="padding-left-16">
                                        <div class="checkbox checkbox-inline no-margin no-padding">
                                            <label class="no-padding">
                                                <input type="checkbox" name="id[]" value="{$vo.id}" autocomplete="off">
                                                <span class="text"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        {$vo.name}
                                    </td>
                                    <td>
                                        <a href="{:url('goodsAttr/edit',['id'=>$vo['id']])}" class="btn btn-default btn-sm purple"><i class="fa fa-edit"></i> 编辑</a>
                                        <a href="javascript:void(0);" class="btn btn-default btn-sm danger btn-del"><i class="fa fa-times"></i> 删除</a>
                                    </td>
                                </tr>
                            {/volist}
                        </tbody>
                    </table>
                </div><!--Widget Body-->
            </div><!--Widget-->
        </div>
    </div>
{/block}
{block name="js"}
    <script>
        $(document).ready(function(){
            var del = function(ids,rowsObj){
                $.fruiter.post("{:url('goodsAttr/del')}",{ids:ids},function(data){
                    if(data.code == 1){
                        $(rowsObj).each(function(){
                            $(this).remove();
                        });
                    }else{
                        Notify(data.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                    }
                });
            }
            $(document).on('click','.btn-del',function(){
                var tr = $(this).closest('tr');
                var id = tr.find('input[name="id[]"]').val();
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        del(id,tr);
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
                    Notify('请至少选择一项模型', 'bottom-right', '5000', 'warning', 'fa-warning', true);
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