<div class="widget flat">
    <div class="widget-body">
        <div id="horizontal-form">
            <form role="form" class="form-horizontal form-spec">
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="inputEmail3">规格名称：</label>
                    <div class="col-sm-10">
                        <input type="text" id="spec_name" class="form-control input-sm">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="inputPassword3">显示备注：</label>
                    <div class="col-sm-10">
                        <input type="text"  id="spec_show" class="form-control input-sm">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="inputPassword3"></label>
                    <div class="col-sm-10">
                        <a class="btn btn-default btn-sm" id="spec_add"><i class="fa fa-plus"></i>添加</a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label no-padding-right" for="inputPassword3"></label>
                    <div class="col-sm-10">
                        <table class="table table-bordered table-hover table-center">
                            <thead>
                            <tr>
                                <th>规格值</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/html" id="add_spec">
    <tr>
        <td><input class="input-xs spec_value" type="text" name="spec_value[]"></td>
        <td>
            <a data-action="up" href="javascript:void(0);" class="btn btn-default btn-xs shiny icon-only success btn-move"><i class="fa fa-arrow-up"></i></a>
            <a data-action="down" href="javascript:void(0);" class="btn btn-default btn-xs shiny icon-only success btn-move"><i class="fa fa-arrow-down"></i></a>
            <a href="javascript:void(0);" class="btn btn-danger btn-xs shiny icon-only white btn-del"><i class="fa fa-times"></i></a>
        </td>
    </tr>
</script>
<script>
    $(function(){
        $('#spec_add').click(function(){
            var tbody = $(this).parents('form').find('table tbody');
            tbody.append(template('add_spec'));
            tbody.children(':last-child').find('.btn-del').bind('click',function(){
                var tr = $(this).closest('tr');
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        tr.remove();
                    }
                });
            });
            tbody.children(':last-child').find('.btn-move').bind('click',function(){
                var action = $(this).data('action');
                var tr = $(this).parents('tr');
                var index = tr.index();
                var trs = $(tr).parents('tbody').find('tr');
                if(index == 0 && action == 'up' ){
                    Notify('无法上移', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                }else if(index == (trs.length-1) && action == 'down' ){
                    Notify('无法下移', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                }else{
                    if (action == 'up') {
                        $(tr).insertBefore($(trs[index - 1]));
                    } else if (action == 'down') {
                        $(tr).insertAfter($(trs[index + 1]));
                    }
                }
            });
        });
    });
</script>