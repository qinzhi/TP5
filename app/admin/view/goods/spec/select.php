<div class="widget">
    <div class="row padding-10">
        <div class="col-md-5">
            <h7>请选择规格</h7>
            <div class="well well-sm bg-white well-select-spec">
                <ul class="ul-spec-list">
                    {volist name="specs" id="vo"}
                        <li data-id="{$vo.id}">
                            <label>{$vo.name}</label>
                            <i class="fa fa-check spec-status"></i>
                        </li>
                    {/volist}
                </ul>
            </div>
        </div>
        <div class="col-md-7">
            <h7>规格预览区</h7>
            <div class="well well-xm bg-white well-pvw-spec">
                <p class="text-success">请在左侧列表选择规格！</p>
                <div class="well-spec-list"></div>
            </div>
        </div>
    </div>
    <div class="row padding-left-10 padding-bottom-10">
        <div class="col-md-12">
            <h7 class="help-block">没有找到需要的规格？</h7>
            <a class="btn btn-default btn-sm btn-add_spec"><i class="fa fa-plus"></i>添加新规格</a>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.ul-spec-list li').click(function(){
            if(!$(this).hasClass('active')){
                $(this).addClass('active').siblings('.active').removeClass('active');
                var spec_list = $('.well-spec-list');
                spec_list.html('<p class="text-muted">数据获取中...</p>');
                $.post('{:url("goodsSpec/get")}',{id:$(this).data('id')},function(data){
                    if(data && data.value){
                        spec_list.data('name',data.name);
                        spec_list.data('value',data.value);
                        spec_list.data('id',data.id);
                        spec_list.data('type',data.type);
                        var spec = '';
                        for(var i in data.value){
                            spec += '<a class="btn btn-default" href="javascript:void(0);">'+ data.value[i] + '</a>&nbsp;';
                        }
                        spec_list.html(spec);
                    }else{
                        spec_list.html('没数据...');
                    }
                });
            }
        });
        $('.btn-add_spec').click(function(){
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
                            $('.ul-spec-list').append('<li data-id="'+data.id+'"><label>'+data.name+'</label></li>');
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
    });
</script>