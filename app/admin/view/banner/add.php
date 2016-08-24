<extend name="Layout/base" />
<block name="content">
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin">
                <div class="widget-header widget-fruiter">
                    <div class="pull-right">
                        <a class="btn btn-success" id="banner_save" href="javascript:void(0);">确定添加</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body plugins_goods-">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-xs-12">
                            <form class="tabbable" id="bannerForm" method="post" autocomplete="off">
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
                                                <th>广告名称：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <input id="name" name="name" class="input-sm Lwidth400" type="text" pattern="required" maxlength="50">
                                                        <span class="note control-label margin-left-10">*</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>广告简述：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <input id="intro" name="intro" class="input-sm Lwidth400" type="text" maxlength="120">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>广告位：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <input id="position" name="position" class="input-sm Lwidth400" pattern="required" type="text" readonly>
                                                        <input id="position_id" name="position_id" type="text" readonly>
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
                                                <th>广告图片：</th>
                                                <td>
                                                    <div class="form-group has-feedback no-margin">
                                                        <div class="input-group input-group-sm Lwidth400">
                                                            <input type="text" class="form-control" pattern="required" readonly>
                                                            <span class="input-group-btn">
                                                                <button type="button" class="btn btn-default btn-success">选择图片</button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>链接地址：</th>
                                                <td>
                                                    <input class="form-control input-sm Lwidth400" id="link" name="link" value="0" pattern="required" type="text" maxlength="255">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>开始和结束时间：</th>
                                                <td>
                                                    <input class="form-control input-sm Lwidth400" id="valid_time" pattern="required" type="text" readonly>
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
    <script src="__JS__/datetime/bootstrap-datepicker.js"></script>
    <script src="__JS__/datetime/moment.js"></script>
    <script src="__JS__/datetime/daterangepicker.js"></script>
    <script>
        $(document).ready(function(){
            $('#valid_time').daterangepicker({
                format: 'YYYY/MM/DD',
                applyClass: 'btn-success',
                locale: {
                    fromLabel: '自',
                    toLabel: '到',
                    applyLabel: '确定',
                    cancelLabel: '取消',
                    monthNames: ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
                    daysOfWeek: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"]
                }
            });
            $('#banner_save').click(function(){
                var form = document.getElementById('bannerForm');
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

            $("#position").attr("value", name);
            $("#position_id").attr("value", id);

        }

        $('#position').click(function(){
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
            if (!(event.target.id == "position" || event.target.id == "tree_panel" || $(event.target).parents("#tree_panel").length>0)) {
                hideMenu();
            }
        }

        $(document).ready(function(){

            create_category_panel();

            zTree = $.fn.zTree.init($("#tree_category"), setting, zNodes);

            $('#goods_save').click(function(){
                var form = document.getElementById('goodsForm');
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
        });
    </script>
</block>