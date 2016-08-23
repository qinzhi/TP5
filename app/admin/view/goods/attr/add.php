{extend name="layout/base" /}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin">
                <div class="widget-header widget-fruiter">
                    <a class="btn btn-success" id="attr_save" href="javascript:void(0);">添加</a>
                </div><!--Widget Header-->
                <div class="widget-body plugins_attr-">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form class="tabbable" id="attrForm" method="post" autocomplete="off">
                                <ul class="nav nav-tabs tabs-flat">
                                    <li class="active">
                                        <a href="#tab-basic" data-toggle="tab">
                                            基本信息
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content tabs-flat">
                                    <div class="tab-pane active" id="tab-basic">
                                        <table class="table-form col-md-8">
                                            <colgroup>
                                                <col width="150px">
                                                <col>
                                            </colgroup>
                                            <tbody>
                                                <tr>
                                                    <th>模型名称：</th>
                                                    <td>
                                                        <div class="form-group has-feedback no-margin">
                                                            <input id="name" name="name" class="input-sm Lwidth400" type="text" pattern="required" maxlength="50">
                                                            <span class="note control-label margin-left-10">*</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>添加扩展属性：</th>
                                                    <td>
                                                        <a class="btn btn-success btn-sm pull-left no-radius" href="javascript:void(0);" id="addAttr">
                                                            <i class="fa fa-plus"></i>
                                                            添加扩展属性
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <td>
                                                        <table class="table table-bordered table-condensed table-middle flip-content dataTable table-center">
                                                            <thead class="flip-content bordered-palegreen">
                                                            <tr>
                                                                <th width="15%">属性名</th>
                                                                <th width="20%">操作样式</th>
                                                                <th width="45%">选择项数据【每项数据之间用逗号','做分割】</th>
                                                                <th width="20%">操作</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="tbody-attr_list"></tbody>
                                                        </table>
                                                    </td>
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
    <script type="text/html" id="model_list">
        <tr>
            <td class="padding-left-20 padding-right-20">
                <input type="text" name="attr_name[]" maxlength="20" class="input-xs form-control" pattern="required">
            </td>
            <td class="padding-left-20 padding-right-20">
                <select name="type[]" class="input-xs form-control">
                    <option value="1">输入框</option>
                    <option value="2">下拉</option>
                    <option value="3">单选</option>
                    <option value="4">复选</option>
                </select>
            </td>
            <td class="padding-left-20 padding-right-20">
                <input type="text" name="value[]" class="input-xs form-control">
            </td>
            <td>
                <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="up"><i class="fa fa-arrow-up"></i></a>
                <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="down"><i class="fa fa-arrow-down"></i></a>
                <a class="btn btn-danger btn-xs shiny icon-only white btn-del" href="javascript:void(0);"><i class="fa fa-times"></i></a>
            </td>
        </tr>
    </script>
    <script>
        $(function(){
            $('#attr_save').click(function(){
                var form = document.getElementById('attrForm');
                if($.validateOnSubmit(form) == true){
                    $(form).submit();
                }
            });
            $('#addAttr').click(function(){
                $('.tbody-attr_list').append(template('model_list'));
            });
            $(document).on('click','.btn-move',function(){
                var action = $(this).data('action');
                var tr = $(this).closest('tr');
                var trs = $(tr).closest('tbody').find('tr');
                var index = trs.index(tr);
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
            $(document).on('click','.btn-del',function(){
                var tr = $(this).closest('tr');
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        tr.remove();
                    }
                });
            });
        });
    </script>
{/block}