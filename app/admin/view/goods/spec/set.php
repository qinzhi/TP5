<div class="widget flat no-margin">
    <div class="widget-header padding-top-5 padding-right-10">
        <h7 class="pull-left margin-top-4">1、增加规格项或选择规格标签 >> 2、添加需要的规格值 >> 保存 </h7>
        <a class="btn btn-default btn-sm pull-right btn-spec"><i class="fa fa-plus"></i>增加规格项</a>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="tabbable clear">
                <ul class="nav nav-tabs tabs-flat tabs-spec-name"></ul>
                <div class="tab-content tabs-flat tabs-spec-list"></div>
            </div>
        </div>
    </div>
</div>
<script id="tabs_spec_name" type="text/html">
    <li data-id="{%id%}" data-name="{%name%}" data-type="{%type%}" class="active">
        <a data-toggle="tab" class="no-padding-right" href="#spec{%id%}">{%name%}<button class="close pull-right tabs-del"> × </button> </a>
    </li>
</script>
<script id="tabs_spec_list" type="text/html">
    <div data-id="{%id%}" id="spec{%id%}" class="tab-pane active">
        <p class="text-success"><span class="text-danger">点击选择</span>下列《{%name%}》：如果没有您需要的《{%name%}》？请到规格列表中编辑{%name%}</p>
        <div class="div-spec_value">
            {%each value as val key%}
                <a href="javascript:void(0);" data-index="{%key%}" data-value="{%val%}" class="btn btn-default btn-spec_value">{%val%}</a>
            {%/each%}
        </div>
        <table class="table table-bordered table-hover table-center margin-top-10">
            <thead>
                <tr>
                    <th>规格值</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</script>
<script id="spec_tbody_list" type="text/html">
    <tr data-value="{%value%}" data-index="{%key%}">
        <td>{%value%}</td>
        <td>
            <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="up"><i class="fa fa-arrow-up"></i></a>
            <a class="btn btn-default btn-xs shiny icon-only success btn-move" href="javascript:void(0);" data-action="down"><i class="fa fa-arrow-down"></i></a>
            <a class="btn btn-danger btn-xs shiny icon-only white btn-del" href="javascript:void(0);"><i class="fa fa-times"></i></a>
        </td>
    </tr>
</script>
<script>
    $(function(){
        $('.btn-spec').click(function(){
            $.dialog({
                id : 'selectSpec',
                title : '选择规格',
                async : false,
                min_width: 600,
                min_height: 350,
                content : function(){
                    var has_id = [];
                    $('.tabs-spec-name > li').each(function(i){
                        has_id.push($(this).data('id'));
                    });
                    var obj = $.post("{:url('goods/spec')}",{tpl:'select',has_id:has_id});
                    return obj.responseText;
                },
                ok : function(target){
                    var spec_list = $(target).find('.well-spec-list');
                    var spec = {};
                    spec.name = spec_list.data('name');
                    spec.value = spec_list.data('value');
                    spec.id = spec_list.data('id');
                    spec.type = spec_list.data('type');
                    if(spec.id && spec.value){

                        var tabs_spec_name = $('.tabs-spec-name');

                        tabs_spec_name.find('.active').removeClass('active');

                        tabs_spec_name.append(template('tabs_spec_name',spec));

                        tabs_spec_name.find('li:last-child').find('.tabs-del').bind('click',function(){
                            var obj = $(this);
                            bootbox.confirm("确定要删除么?", function (result) {
                                if(result){
                                    var li = obj.parents('li');
                                    var id = li.data('id');
                                    var anchor = li.find('a').attr('href');
                                    $(anchor).remove();
                                    li.remove();
                                }
                            });
                        });

                        var tabs_spec_list = $('.tabs-spec-list');
                        tabs_spec_list.find('.active').removeClass('active');

                        tabs_spec_list.append(template('tabs_spec_list',spec));

                        tabs_spec_list.children(':last-child').find('.btn-spec_value').bind('click',function(){

                            this.setAttribute('disabled',true);
                            var value = $(this).data('value');

                            var tbody = $(this).closest('.tab-pane').find('table tbody');

                            tbody.append(template('spec_tbody_list',{value:value,key:$(this).index()}));

                            tbody.children(':last-child').find('.btn-del').bind('click',function(){
                                var tr = $(this).closest('tr');
                                bootbox.confirm("确定要删除么?", function (result) {
                                    if(result){
                                        var spec_value = $(tr).data('value');
                                        var index = $(tr).data('index');
                                        var btn_spec_value = $(tr).closest('.tab-pane').find('.btn-spec_value').get(index);
                                        console.log($(tr).closest('.tab-pane').find('.btn-spec_value'));
                                        $(btn_spec_value).removeAttr('disabled');
                                        tr.remove();
                                    }
                                });
                            });
                            tbody.children(':last-child').find('.btn-move').bind('click',function(){
                                var action = $(this).data('action');
                                var tr = $(this).closest('tr');
                                var index = tr.index();
                                var trs = $(tr).closest('tbody').find('tr');
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
                    }
                }
            });
        });
    });
</script>