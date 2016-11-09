{extend name="Layout/base" /}
{block name="css"}
    <link href="__CSS__/weixin.css" rel="stylesheet" />
{/block}
{block name="content"}
    <div class="row no-margin">
        <div class="menu_action">
            <a class="btn btn-success" id="release">保存并发布</a>
        </div>
        <div class="menu_setting_area">
            <div class="menu_preview_area">
                <div class="mobile_menu_preview">
                    <div class="mobile_hd">桃江通</div>
                    <div class="mobile_bd">
                        <ul class="pre_menu_list flex">
                            {if condition="!empty($menu)"}
                                <?php foreach($menu['button'] as $key => $value):?>
                                    <li class="pre_menu_item menu_item flex-1" data-name="{$value.name}"
                                        <?php if(!empty(!empty($value['type']))){echo "data-type='" . $value['type'] ."'";}?>
                                        <?php if(!empty(!empty($value['url']))){echo "data-url='" . $value['url'] ."'";}?>
                                        <?php if(!empty(!empty($value['key']))){echo "data-keyword='" . $value['key'] ."'";}?>>
                                        <a class="pre_menu_link menu_link" href="javascript:;"><span>{$value.name}</span></a>
                                        <div class="sub_menu_box">
                                            <ul class="sub_menu_list">
                                                <?php if(!empty($value['sub_button']))
                                                      foreach($value['sub_button'] as $k => $sub):
                                                ?>
                                                    <li class="sub_menu_item" data-name="{$sub.name}" data-type="{$sub.type}"
                                                        <?php if(!empty(!empty($sub['url']))){echo "data-url='" . $sub['url'] ."'";}?>
                                                        <?php if(!empty(!empty($sub['key']))){echo "data-keyword='" . $sub['key'] ."'";}?>>
                                                        <a class="sub_menu_link menu_link"><span>{$sub.name}</span></a>
                                                    </li>
                                                <?php endforeach;?>
                                                <li class="sub_menu_item no-extra <?php if(!empty($value['sub_button']) && count($value['sub_button']) >= 5){echo 'hidden';}?>">
                                                    <a class="sub_menu_link menu_link js_addLBtn" title="最多添加5个子菜单"><i class="fa fa-plus"></i></a>
                                                </li>
                                            </ul>
                                            <i class="arrow arrow_out"></i>
                                            <i class="arrow arrow_in"></i>
                                        </div>
                                    </li>
                                <?php endforeach;?>
                            {else /}
                                <li class="pre_menu_item menu_item flex-1 active" data-name="菜单名称" data-type="view">
                                    <a class="pre_menu_link menu_link" href="javascript:;"><span>菜单名称</span></a>
                                    <div class="sub_menu_box">
                                        <ul class="sub_menu_list">
                                            <li class="sub_menu_item no-extra">
                                                <a class="sub_menu_link menu_link js_addLBtn" title="最多添加5个子菜单"><i class="fa fa-plus"></i></a>
                                            </li>
                                        </ul>
                                        <i class="arrow arrow_out"></i>
                                        <i class="arrow arrow_in"></i>
                                    </div>
                                </li>
                            {/if}
                            <li class="pre_menu_item flex-1 no-extra  <?php if(!empty($menu['button']) && count($menu['button']) >= 3){echo 'hidden';}?>">
                                <a class="pre_menu_link menu_link js_addL1Btn" title="最多添加3个一级菜单" href="javascript:;"><i class="fa fa-plus"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="menu_form_area">
                <div class="shade_tips"><p>点击左侧菜单进行编辑操作</p></div>
                <div class="widget flat">
                    <div class="widget-header">
                        <span class="widget-caption">编辑菜单</span>
                        <div class="widget-buttons buttons-bordered">
                            <button class="btn btn-darkorange btn-xs btn-del">删除菜单</button>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div id="js_preMenuTips" class="pre_menu_tips hidden">已添加子菜单，仅可设置菜单名称。</div>
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="name">菜单名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-control" autocomplete="off">
                                    <p class="help-block help-block-name">字数不超过4个汉字或8个字母</p>
                                </div>
                            </div>
                            <div class="form-group no-padding">
                                <label class="col-sm-2 control-label no-padding-right" for="name">菜单内容</label>
                                <div class="col-sm-10">
                                    <span class="control-group">
                                        <div class="radio line-radio">
                                            <label class="no-padding">
                                                <input type="radio" value="0" name="status" autocomplete="off" disabled>
                                                <span class="text">发送消息</span>
                                            </label>
                                        </div>
                                        <div class="radio line-radio">
                                            <label>
                                                <input type="radio" value="1" data-type="view" name="status" autocomplete="off">
                                                <span class="text">跳转网页</span>
                                            </label>
                                        </div>
                                        <div class="radio line-radio">
                                            <label>
                                                <input type="radio" value="2" data-type="click" name="status" autocomplete="off">
                                                <span class="text">触发关键字</span>
                                            </label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="menu_content_container">
                                <div class="menu_content menu_content_edit">
                                    <label class="col-sm-2 control-label no-padding-right" for="name"></label>
                                    <div class="menu_edit col-sm-10">
                                        <ul class="tab_navs">
                                            <li class="tab_nav tab_text active">
                                                <a onclick="return false;" href="javascript:void(0);">
                                                    <i class="icon_msg_sender"></i>
                                                    <span class="msg_tab_title">文字</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tabs_content">
                                            <div class="tab_type tab_type_text">
                                                <textarea class="text_txt" id="text_txt" autocomplete="off"></textarea>
                                                <div class="footer_toolbar">
                                                    <a class="icon_emotion" href="javascript:void(0);">表情</a>
                                                    <p class="text_tip">还可以输入<em>600</em>字</p>
                                                    <div class="emotion_area hidden">
                                                        <ul class="emotions">
                                                            {volist name="emotions" id="vo"}
                                                                <li class="emotions_item">
                                                                    <i class="emotion_i" style="background-position:-{$key * 24}px 0;"
                                                                       data-title="{$vo.title}" data-gifurl="{$vo.gifurl}"></i>
                                                                </li>
                                                            {/volist}
                                                        </ul>
                                                        <span class="emotions_preview">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="menu_content menu_content_url hidden">
                                    <label class="col-sm-2 control-label no-padding-right" for="name"></label>
                                    <div class="menu_url col-sm-10">
                                        <p>指定点击此菜单时要跳转的链接（注：链接需加http://）</p>
                                        <div class="row">
                                            <label class="col-md-2 frm_label">页面地址</label>
                                            <div class="col-md-10 frm_controls">
                                                <input type="text" id="site_url" name="site_url" class="form-control" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="menu_content menu_content_key hidden">
                                    <label class="col-sm-2 control-label no-padding-right" for="name"></label>
                                    <div class="menu_url col-sm-10">
                                        <p>指定点击此菜单时要执行的操作, 你可以在这里输入关键字</p>
                                        <div class="row">
                                            <label class="col-md-2 frm_label">关键字</label>
                                            <div class="col-md-10 frm_controls">
                                                <input type="text" id="keyword" name="keyword" class="form-control" autocomplete="off"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}
{block name="js"}
    <script type="text/html" id="menuTpl">
        <li class="pre_menu_item menu_item flex-1" data-name="菜单名称" data-type="view">
            <a class="pre_menu_link menu_link" href="javascript:;">
                <span>菜单名称</span>
            </a>
            <div class="sub_menu_box">
                <ul class="sub_menu_list">
                    <li class="sub_menu_item no-extra">
                        <a class="sub_menu_link menu_link js_addLBtn" title="最多添加5个子菜单"><i class="fa fa-plus"></i></a>
                    </li>
                </ul>
                <i class="arrow arrow_out"></i>
                <i class="arrow arrow_in"></i>
            </div>
        </li>
    </script>
    <script type="text/html" id="subMenuTpl">
        <li class="sub_menu_item" data-name="子菜单名称">
            <a class="sub_menu_link menu_link" href="javascript:;"><span>子菜单名称</span></a>
        </li>
    </script>
    <script>
        $(function(){
            var menu = {
                entity: null,
                shade_tips: $('.shade_tips'),
                set_entity: function(entity){
                    this.entity = entity;
                    this.form();
                    return this;
                },
                form: function(){
                    $('input[name="name"]').val(this.get_name());
                    var type = this.get_type();
                    var status = $('input[name="status"]');
                    $('#site_url').val('');
                    $('#keyword').val('');
                    if(type == 'view'){
                        $('#site_url').val(this.get_url());
                        var target = $(status.get(1));
                        status.get(1).checked = true;
                    }else if(type == 'click'){
                        $('#keyword').val(this.get_keyword());
                        var target = $(status.get(2));
                        status.get(2).checked = true;
                    }else{
                        var target = $(status.get(0));
                        status.get(0).checked = true;
                    }
                    this.chang_show(target);
                    return this;
                },
                set_type: function(type){
                    this.entity.data('type',type);
                    return this;
                },
                get_type: function(){
                    return this.entity.data('type');
                },
                set_name: function(name){
                    this.entity.data('name',name);
                    this.entity.find(' > .menu_link span').text(name);
                    return this;
                },
                get_name: function(){
                    return this.entity.data('name');
                },
                set_key: function(key){
                    this.entity.data('key',key);
                    return this;
                },
                get_key: function(){
                    return this.entity.data('key');
                },
                set_url: function(url){
                    console.log(this.entity);
                    this.entity.data('url',url);
                    return this;
                },
                get_url: function(){
                    return this.entity.data('url');
                },
                set_keyword: function (keyword) {
                    return this.entity.data('keyword',keyword);
                },
                get_keyword: function () {
                    return this.entity.data('keyword');
                },
                set_media_id: function(media_id){
                    this.entity.data('media_id',media_id);
                    return this;
                },
                get_media_id: function(){
                    return this.entity.data('media_id');
                },
                chang_show: function(obj){
                    var value = 0;
                    obj.each(function(e){
                        if(this.checked && this.value==1)return value = 1;
                    });
                    this.set_type(obj.data('type'));
                    var index = $(obj).closest('.radio').index();
                    $($('.menu_content').addClass('hidden').get(index)).removeClass('hidden');
                    return this;
                },
                hide_shade: function () {
                    this.shade_tips.hide();
                    return this;
                },
                show_shade: function () {
                    this.shade_tips.show();
                    return this;
                },
                show_addBtn: function () {
                    this.entity.parent().find('li:last-child').removeClass('hidden');
                    return this;
                },
            }.set_entity($('.pre_menu_item.active'));

            $('.emotions_item').hover(function(){
                $('.emotions_preview').html('<img src="' + $(this).find('i').data('gifurl') + '"/>');
            },function(){
                $('.emotions_preview').html('');
            });
            $('.emotions_item').click(function(){
                var text_txt = $('#text_txt').val();
                var text = $(this).children().data('title');
                $('#text_txt').val(text_txt + '[' + text + ']');
                $('.emotion_area').addClass('hidden');
            });
            $('.icon_emotion').click(function(){
                var emotion_area = $('.emotion_area');
                if(emotion_area.hasClass('hidden')){
                    emotion_area.removeClass('hidden');
                }else{
                    emotion_area.addClass('hidden');
                }
            });
            $('input[name="status"]').change(function(){
                menu.chang_show($(this));
            });
            $(document).on('click','.pre_menu_item',function(e){
                var cur_li = $(e.target).closest('li');
                if(!cur_li.hasClass('pre_menu_item')){
                    return;
                }
                var ul = $(this).parent();
                if(!$(this).hasClass('no-extra')){
                    if($(this).hasClass('active-invalid')) $(this).removeClass('active-invalid');
                    cur_li.find('li.active').removeClass('active');

                    if(!$(this).hasClass('active')){
                        ul.children('.active').removeClass('active');
                        $(this).addClass('active');
                    }
                    $('.help-block-name').text('字数不超过4个汉字或8个字母');
                    menu.hide_shade().set_entity($(this));
                    var widget = $('.menu_form_area').find('.widget');
                    if(cur_li.find('ul.sub_menu_list').find('li.sub_menu_item').length > 1){ //判断是否有子菜单
                        widget.find('#js_preMenuTips').removeClass('hidden');
                        widget.find('.form-horizontal > div').hide();
                        widget.find('.form-horizontal > div:first-child').show();
                    }else{
                        widget.find('.form-horizontal > div').show();
                        widget.find('#js_preMenuTips').addClass('hidden');
                    }
                }else{
                    var li = template('menuTpl');
                    $(this).before(li);
                    ul.children('.active').removeClass('active');
                    $(this).prev().addClass('active');
                    $('.help-block-name').text('字数不超过4个汉字或8个字母');
                    menu.hide_shade().set_entity($(this).prev());
                    if(ul.children().length === 4){
                        $(this).addClass('hidden');
                    }
                }
            });            
            $(document).on('click','.sub_menu_item',function () {
                var parent_ul = $(this).closest('.pre_menu_list');
                var parent_li = $(this).closest('.pre_menu_item');
                var cur_ul = $(this).parent();
                var cur_li = $(this);

                if(!$(this).hasClass('no-extra')){
                    if(!$(this).hasClass('active')){
                        parent_li.addClass('active-invalid');
                        cur_ul.children('.active').removeClass('active');
                        $(this).addClass('active');
                        $('.help-block-name').text('字数不超过8个汉字或16个字母');
                        var widget = $('.menu_form_area').find('.widget');
                        widget.find('.form-horizontal > div').show();
                        widget.find('#js_preMenuTips').addClass('hidden');
                        menu.hide_shade().set_entity($(this));
                    }
                }else{
                    var li = template('subMenuTpl');
                    $(this).before(li);
                    parent_ul.children('.active').addClass('active-invalid');
                    cur_ul.children('.active').removeClass('active');
                    $(this).prev().addClass('active');
                    $('.help-block-name').text('字数不超过8个汉字或16个字母');
                    menu.hide_shade().set_entity($(this).prev());
                    if(cur_ul.children().length === 6){
                        $(this).addClass('hidden');
                        cur_ul.parent().find('.arrow').hide();
                    }
                }
            });
            $('.btn-del').click(function () {
                bootbox.confirm("确定要删除么?", function (result) {
                    if(result){
                        menu.show_shade().show_addBtn().entity.remove();
                    }
                });
            });
            $('input[name="name"]').keyup(function(){
                var value = $.trim(this.value);
                menu.set_name(value);
            });
            $('#site_url').keyup(function(){
                var value = $.trim(this.value);
                menu.set_url(value);
            });
            $('#keyword').keyup(function () {
                var value = $.trim(this.value);
                menu.set_keyword(value);
            });
            $('#release').click(function(){
                var buttons = [];
                var menus = $('.pre_menu_list').children();
                for(var i= 0,ilen=(menus.length-1) ;i<ilen;i++){
                    buttons[i] = {};
                    var name = $(menus[i]).data('name');
                    if(name == ''){
                        return Notify('菜单名称不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                    }
                    buttons[i].name = name;
                    var child_menu = $(menus[i]).find('.sub_menu_list').find('.sub_menu_item');
                    if(child_menu.length > 1){
                        buttons[i].sub_button = [];
                        for(var j=0,jlen=(child_menu.length-1);j<jlen;j++){
                            buttons[i].sub_button[j] = {};
                            var name = $(child_menu[j]).data('name')
                            if(!name){
                                return Notify('菜单名称不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                            }
                            buttons[i].sub_button[j].name = name;
                            buttons[i].sub_button[j].type = $(child_menu[j]).data('type');
                            if(buttons[i].sub_button[j].type == 'view'){ //连接地址
                                var url = $(child_menu[j]).data('url');
                                if(!url || url.indexOf('http://') === -1){
                                    return Notify('页面地址不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                                }
                                buttons[i].sub_button[j].url = url;
                            }else if(buttons[i].sub_button[j].type == 'click'){ //触发关键字
                                var keyword = $(child_menu[j]).data('keyword');
                                buttons[i].sub_button[j].key = keyword;
                            }
                        }
                    }else{
                        buttons[i].type = $(menus[i]).data('type');
                        if(buttons[i].type == 'view'){ //连接地址
                            var url = $(menus[i]).data('url');
                            if(url == ''){
                                return Notify('页面地址不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                            }
                            buttons[i].url = url;
                        }else if(buttons[i].type == 'click'){ //触发关键字
                            var keyword = $(menus[i]).data('keyword');
                            buttons[i].key = keyword;
                        }
                    }
                }
                $.post('',{buttons:buttons},function(result){
                    if(result.code == 1){
                        Notify(result.msg, 'bottom-right', '5000', 'success', 'fa-bolt', true);
                    }else{
                        Notify(result.msg, 'bottom-right', '5000', 'danger', 'fa-bolt', true);
                    }
                });
            });
        });
    </script>
{/block}