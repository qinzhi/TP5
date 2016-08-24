<extend name="Layout/base" />
<block name="content">
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin">
                <div class="widget-header widget-fruiter">
                    <div class="pull-right">
                        <a class="btn btn-success" id="position_save" href="javascript:void(0);">确定添加</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body plugins_goods-">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form class="tabbable" id="positionForm" method="post" autocomplete="off">
                                <ul class="nav nav-tabs tabs-flat">
                                    <li class="active">
                                        <a href="#tab-basic" data-toggle="tab">
                                            基本信息
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
                                                    <th>广告位名称：</th>
                                                    <td>
                                                        <div class="form-group has-feedback no-margin">
                                                            <input id="name" name="name" class="input-sm Lwidth400" type="text" pattern="required" maxlength="50">
                                                            <span class="note control-label margin-left-10">*</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>广告位简述：</th>
                                                    <td>
                                                        <div class="form-group has-feedback no-margin">
                                                            <input id="intro" name="intro" class="input-sm Lwidth400" type="text" maxlength="120">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>开启状态：</th>
                                                    <td>
                                                        <span class="control-group">
                                                            <div class="radio line-radio">
                                                                <label class="no-padding">
                                                                    <input name="status" type="radio" checked="checked" value="1">
                                                                    <span class="text">开启</span>
                                                                </label>
                                                            </div>
                                                            <div class="radio line-radio">
                                                                <label>
                                                                    <input name="status" type="radio" value="0">
                                                                    <span class="text">关闭</span>
                                                                </label>
                                                            </div>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>广告位宽度：</th>
                                                    <td>
                                                        <div class="form-group has-feedback no-margin">
                                                            <div class="form-group has-feedback no-margin">
                                                                <div class="form-group no-margin">
                                                                    <div class="input-group input-sm Lwidth400 no-padding">
                                                                        <input class="form-control" id="width" name="width" value="0" pattern="required" type="text" maxlength="5">
                                                                        <span class="input-group-addon">px</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>广告位高度：</th>
                                                    <td>
                                                        <div class="form-group has-feedback no-margin">
                                                            <div class="form-group no-margin">
                                                                <div class="input-group input-sm Lwidth400 no-padding">
                                                                    <input class="form-control" id="height" name="height" value="0" pattern="required" type="text" maxlength="5">
                                                                    <span class="input-group-addon">px</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>排序：</th>
                                                    <td>
                                                        <div class="form-group has-feedback no-margin">
                                                            <div class="spinner spinner-right Lwidth400">
                                                                <input type="text" name="sort" class="spinner-input form-control" pattern="int" value="0">
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
</block>
<block name="js">
    <script>
        $(document).ready(function(){

            $('#position_save').click(function(){
                var form = document.getElementById('positionForm');
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
</block>