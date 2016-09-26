<table class="table-form">
    <colgroup>
        <col width="150px"><col>
    </colgroup>
    <tbody>
        <tr>
            <th>商品名称：</th>
            <td>
                <div class="form-group has-feedback no-margin">
                    <input id="name" name="name" class="input-sm Lwidth400" type="text" pattern="required" maxlength="50">
                    <span class="note control-label margin-left-10">*</span>
                </div>
            </td>
        </tr>
        <tr>
            <th>商品简述：</th>
            <td>
                <div class="form-group has-feedback no-margin">
                    <span class="input-icon icon-right Lwidth400">
                        <textarea id="intro" name="intro" class="form-control" maxlength="128"></textarea>
                        <i class="fa fa-rocket darkorange"></i>
                    </span>
                </div>
            </td>
        </tr>
        <tr>
            <th>关键字：</th>
            <td>
                <div class="form-group has-feedback no-margin">
                    <input id="search_words" name="search_words"  type="text" class="input-sm Lwidth300" maxlength="50"/>
                    <span class="note control-label margin-left-10">每个关键词最长为15个字符，必须以","(逗号)分隔符</span>
                </div>
            </td>
        </tr>
        <tr>
            <th>所属分类：</th>
            <td>
                <div class="form-group has-feedback no-margin">
                    <input id="category" class="input-sm Lwidth400" type="text">
                    <input id="category_id" name="category_id" class="hidden" type="text">
                </div>
            </td>
        </tr>
        <tr>
            <th>是否上架：</th>
            <td>
                <span class="control-group">
                    <div class="radio line-radio">
                        <label class="no-padding">
                            <input name="status" type="radio" checked="checked" value="0">
                            <span class="text">下架</span>
                        </label>
                    </div>
                    <div class="radio line-radio">
                        <label>
                            <input name="status" type="radio" value="1">
                            <span class="text">上架</span>
                        </label>
                    </div>
                </span>
            </td>
        </tr>
        <tr>
            <th>基本数据：</th>
            <td>
                <table class="table table-bordered table-hover table-border">
                    <thead>
                        <tr>
                            <th class="base">商品货号</th>
                            <th class="base">库存</th>
                            <th class="base">市场价格</th>
                            <th class="base">销售价格</th>
                            <th class="base">成本价格</th>
                            <th class="base">重量(克)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="base">
                            <td class="base">
                                <div class="form-group has-feedback no-margin">
                                    <input class="input-xs Lwidth150" type="text" name="_goods_no[]" pattern="required" maxlength="20">
                                </div>
                            </td>
                            <td class="base">
                                <div class="form-group has-feedback no-margin">
                                    <input class="input-xs Lwidth80" type="text" name="_store_nums[]" pattern="int" maxlength="10">
                                </div>
                            </td>
                            <td class="base">
                                <div class="form-group has-feedback no-margin">
                                    <input class="input-xs Lwidth80" type="text" name="_market_price[]" pattern="float" maxlength="10">
                                </div>
                            </td>
                            <td class="base">
                                <div class="form-group has-feedback no-margin">
                                    <input class="input-xs Lwidth80" type="text" name="_sell_price[]" pattern="float" maxlength="10">
                                </div>
                            </td>
                            <td class="base">
                                <div class="form-group has-feedback no-margin">
                                    <input class="input-xs Lwidth80" type="text" name="_cost_price[]" pattern="float" maxlength="10">
                                </div>
                            </td>
                            <td class="base">
                                <div class="form-group has-feedback no-margin">
                                    <input class="input-xs Lwidth80" type="text" name="_weight[]" pattern="float" maxlength="10">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>商品规格：</th>
            <td>
                <a class="btn btn-success btn-sm pull-left no-radius" href="javascript:void(0);" id="addSpec" data-status="true">
                    <i class="fa fa-plus"></i>
                    添加规格
                </a>
                <span class="note margin-left-10 pull-left margin-top-4">可以生成不同组合参数的货品，比如：尺码xxl+红色+长袖 衣服</span>
            </td>
        </tr>
        <tr>
            <th>计量单位显示：</th>
            <td>
                <div class="form-group has-feedback no-margin"> 
                    <select name="unit" id="unit">
                        {volist name="unit" id="vo"}
                            <option value="{$vo.name}">{$vo.name}</option>
                        {/volist}
                    </select>
                </div>
            </td>
        </tr>
        <tr>
            <th>商品类型：</th>
            <td>
                <div class="checkbox checkbox-inline no-margin no-padding">
                    <label class="no-padding">
                        <input type="checkbox" name="commend_type[]" value="1">
                        <span class="text">最新商品</span>
                    </label>
                </div>
                <div class="checkbox checkbox-inline no-margin">
                    <label>
                        <input type="checkbox" name="commend_type[]" value="2">
                        <span class="text">特价商品</span>
                    </label>
                </div>
                <div class="checkbox checkbox-inline no-margin">
                    <label>
                        <input type="checkbox" name="commend_type[]" value="3">
                        <span class="text">热卖商品</span>
                    </label>
                </div>
                <div class="checkbox checkbox-inline no-margin">
                    <label>
                        <input type="checkbox" name="commend_type[]" value="4">
                        <span class="text">推荐商品</span>
                    </label>
                </div>
            </td>
        </tr>
        <tr>
            <th>商品图片：</th>
            <td>
                <ul class="cover-box">
                    <li class="last" id="add-image"></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>
<script type="text/html" id="coverTpl">
    <li class="goods-img">
        <img src="{%img%}"/>
        <i class="delete glyphicon glyphicon-remove"></i>
        <input type="hidden" name="image[]" value="{%image%}"/>
        {%if (index > 0)%}
            <p class="set-cover">设为封面图</p>
        {%else%}
            <input type="hidden" id="cover-index" name="cover_index" value="{%index%}"/>
            <p class="set-cover active">封面图片</p>
        {%/if%}
    </li>
</script>
<script>
    $(function(){
        new GoodsImage();
        var goods_base = '';
        $('#addSpec').click(function(){
            if($(this).data('status') == false){
                Notify('重新设置规格需删除当前规格列表', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                return;
            }
            $.dialog({
                id : 'setSpec',
                title : '设置商品规格',
                async : false,
                min_width: 600,
                min_height: 350,
                content : function(){
                    var content;
                    $.post("{:url('goods/spec')}",{tpl:'set'},function(data){
                        content = data;
                    });
                    return content;
                },
                ok : function(target){
                    var spec = [];
                    var table = $('.table-border');
                    var thead = table.find('thead');
                    var tbody = table.find('tbody');
                    var tabs_spec = $(target).find('.tabs-spec-name li');
                    if(tabs_spec.length){
                        tabs_spec.each(function(index){
                            var id = $(this).data('id');
                            var name = $(this).data('name');
                            var type = $(this).data('type');
                            spec[index] = [];
                            var tr = $(target).find('div[data-id='+id+']').find('table > tbody > tr');
                            if(tr.length > 0){
                                tr.each(function(i){
                                    spec[index][i] = $(this).data('value');
                                });
                                spec[index]['name'] = name;
                                spec[index]['type'] = type;
                                spec[index]['id'] = id;
                            }else{
                                spec.pop();
                            }
                        });

                        var spec_depth = spec.length;
                        if(spec_depth > 0){

                            if(goods_base == ''){
                                goods_base = tbody.find('tr.base');
                            }
                            tbody.find('tr.base').remove();

                            var th = table.find('thead > tr');
                            var _spec = [];
                            $.each(spec,function(i){
                                var tmp = $('<th class="spec">' + this.name + '</th>');
                                tmp.data('id',this.id)
                                    .data('name',this.name)
                                    .data('type',this.type);
                                _spec[i] = {};
                                _spec[i].id = this.id;
                                _spec[i].name = this.name;
                                _spec[i].type = this.type;
                                th.prepend(tmp);
                                delete this.type;
                                delete this.name;
                                delete this.id;
                            });

                            th.append('<th class="spec">默认</th>');
                            th.append('<th class="spec">操作</th>');

                            var spec_list = Zuhe(spec);

                            for(var i in spec_list){

                                var tr = $('<tr class="spec"></tr>');
                                var list = spec_list[i].split(',');
                                for(var l in list){
                                    var td = $('<td data-value="' + list[l] + '">' + list[l] + '</td>');
                                    tr.append(td);
                                    var index = td.index();
                                    var _input = _spec[index];
                                    _input.value = list[l];
                                    td.append("<input type='hidden' value='" + JSON.stringify(_input) + "' name='_spec_list[" + i + "][]'/>");
                                }

                                var base_tr = goods_base.find('td').clone().removeAttr('class');
                                base_tr.find('.has-error').removeClass('has-error');
                                tr.append(base_tr);

                                var td = $('<td></td>');
                                td.append('<div class="checkbox no-margin"></div>');
                                td.find('div').append('<label class="no-padding-left"></label>');

                                var _checkbox = $('<input type="checkbox" name="_default" value="' + i + '" class="inverted" />');
                                if(i == 0) _checkbox.attr('checked',true);
                                td.find('div > label').append(_checkbox);

                                td.find('div > label').append('<span class="text margin-left-4"></span>');

                                tr.append(td);

                                tr.append('<td><a href="javascript:void(0);" class="btn btn-danger btn-xs shiny icon-only white btn-del"><i class="fa fa-times"></i></a></td>');
                                tbody.append(tr);
                            }

                            $('#addSpec').data('status',false);

                            tbody.find('input[name="_default"]').click(function(){
                                if(this.checked === false){
                                    if(tbody.find('input[name="_default"]:checked').length <= 0){
                                        this.checked = true;
                                    }
                                }else{
                                    var checkbox = tbody.find('input[name="_default"]:checked');
                                    if(checkbox.length > 0){
                                        checkbox.attr('checked',false);
                                    }
                                    this.checked = true;
                                }
                            });

                            tbody.find('.btn-del').click(function(){
                                var spec_td = $(this).closest('tbody').find('tr.spec');
                                if(spec_td.length == 1){
                                    th.find('th.spec').remove();
                                    tbody.html(goods_base);
                                    $('#addSpec').data('status',true);
                                }else{
                                    var tr = $(this).closest('tr');
                                    var checkbox = tr.find('input[type="checkbox"]').get(0);
                                    if(checkbox.checked == true){
                                        tr.remove();
                                        tbody.find('tr:first-child').find('input[type="checkbox"]').attr('checked',true);
                                    }
                                }
                            });
                        }
                    }
                }
            });
        });
    });
    //接受可变长数组参数，具体看下面的测试代码
    function Zuhe(spec){
        var heads=spec[0];
        for(var i=1,len=spec.length;i<len;i++){
            heads=addNewType(heads,spec[i]);
        }
        return heads;
    }

    //在原有组合结果的基础上添加一种新的规格
    function addNewType(heads,choices){
        var result=[];
        for(var i=0,len=heads.length;i<len;i++){
            for(var j=0,lenj=choices.length;j<lenj;j++){
                result.push(heads[i]+','+choices[j]);
            }
        }
        return result;
    }

</script>