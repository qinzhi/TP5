{extend name="layout/base" /}
{block name="content"}
    <div class="row no-margin">
        <div class="col-lg-12 col-sm-12 col-xs-12 no-padding">
            <div class="widget flat no-margin plugins_position-">
                <div class="widget-header widget-fruiter">
                    <form class="pull-left goods_list_top" method="get">
                        <input name="search[keywords]" type="text" class="input-sm Lwidth300" placeholder="广告名称"/>
                        <button class="btn btn-success" id="search" type="submit">搜索</button>
                    </form>
                    <div class="pull-right">
                        <a class="btn btn-success" href="{:url('banner/add')}">添加广告</a>
                    </div>
                </div><!--Widget Header-->
                <div class="widget-body no-padding">
                    <table class="table table-hover table-middle">
                        <colgroup>
                            <col width="60px">
                            <col width="350px">
                            <col width="150px">
                            <col width="100px">
                            <col width="200px">
                            <col width="100px">
                            <col width="160px">
                        </colgroup>
                        <thead class="bordered-success">
                            <tr role="row">
                                <th class="padding-left-16">选择</th>
                                <th>名称</th>
                                <th>广告位</th>
                                <th>排序</th>
                                <th>开始时间 ～ 结束时间</th>
                                <th>开启状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            {volist name="banner" id="vo"}
                                <tr>
                                    <td class="padding-left-16">
                                        <div class="checkbox checkbox-inline no-margin no-padding">
                                            <label class="no-padding">
                                                <input type="checkbox" class="banner_id" name="id[]" value="{$vo.id}" autocomplete="off">
                                                <span class="text"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="input-edit" data-field="name" title="点击更新名称">{$vo.name}</td>
                                    <td>{$vo.position_name}</td>
                                    <td class="input-edit" data-field="sort" title="点击更新排序">{$vo.sort}</td>
                                    <td>{$vo.start_time|date='Y/m/d',###} - {$vo.end_time|date='Y/m/d',###}</td>
                                    <td>
                                        <label class="list-status banner-status">
                                            <input class="checkbox-slider toggle colored-success yesno" type="checkbox" autocomplete="off" {$vo['status']?='checked'}>
                                            <span class="text" title="开启状态"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <a class="btn btn-default btn-sm purple btn-edit" href="{:url('banner/edit',['id'=>$vo['id']])}" title="编辑">
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
            var _banner = {
                update: function (params) {
                    $.fruiter.post('{:url("banner/update")}', params, function (data) {
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
                            params.id = $(this).closest('tr').find('.banner_id').val();
                            params[field] = cur_val;
                            _banner.update(params);
                            obj.text(cur_val);
                        } else {
                            obj.text(val);
                        }
                    });
                }
            });
            $('.btn-del').click(function(){
                var banner_id = $(this).closest('tr').find('.banner_id').val();
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        $.post('{:url("banner/del")}',{id:banner_id},function(result){
                            if(result.code == 1){
                                window.location.reload();
                            }else{
                                Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                            }
                        });
                    }
                });
            });
            $('.position-status input[type=checkbox]').change(function () {
                var params = {};
                params.id = $(this).closest('tr').find('.banner_id').val();
                params.status = this.checked ? 1 : 0;
                _banner.update(params);
            });
        });
    </script>
{/block}