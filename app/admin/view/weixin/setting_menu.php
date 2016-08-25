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
                    <div class="mobile_hd">果度千寻</div>
                    <div class="mobile_bd">
                        <ul class="pre_menu_list flex">
                            {if condition="!empty($menu)"}
                                <?php foreach($menu['button'] as $key => $menu):?>
                                    <li class="pre_menu_item menu_item flex-1 {$key==0?'active':''}" data-name="{$menu.name}" data-type="{$menu.type}" data-url="{$menu.url}">
                                        <a class="pre_menu_link" href="javascript:;"><span>{$menu.name}</span></a>
                                    </li>
                                <?php endforeach;?>
                            {else /}
                                <li class="pre_menu_item menu_item flex-1 active" data-name="菜单名称" data-type="view">
                                    <a class="pre_menu_link" href="javascript:;"><span>菜单名称</span></a>
                                </li>
                            {/if}
                            <li class="pre_menu_item flex-1 no-extra">
                                <a class="pre_menu_link js_addL1Btn" title="最多添加3个一级菜单" href="javascript:;"><i class="fa fa-plus"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="menu_form_area">
                <div class="widget flat">
                    <div class="widget-header">
                        <span class="widget-caption">编辑菜单</span>
                        <div class="widget-buttons buttons-bordered">
                            <button class="btn btn-darkorange btn-xs">删除菜单</button>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-2 control-label no-padding-right" for="name">菜单名称</label>
                                <div class="col-sm-10">
                                    <input type="text" id="name" class="form-control" autocomplete="off">
                                    <p class="help-block">字数不超过4个汉字或8个字母</p>
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
                                                <input type="radio" value="1" name="status" autocomplete="off">
                                                <span class="text">跳转网页</span>
                                            </label>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="menu_content_container">
                                <div class="menu_content_edit">
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
                                            <div class="tabs_type_text">
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
                                <div class="menu_content_url hidden">
                                    <label class="col-sm-2 control-label no-padding-right" for="name"></label>
                                    <div class="menu_url col-sm-10">
                                        <p>订阅者点击该子菜单会跳到以下链接</p>
                                        <div class="row">
                                            <label class="col-md-2 frm_label">页面地址</label>
                                            <div class="col-md-10 frm_controls">
                                                <input type="text" id="site_url" name="site_url" class="form-control" autocomplete="off"/>
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
            <a class="pre_menu_link" href="javascript:;">
                <span>菜单名称</span>
            </a>
        </li>
    </script>
    <script>
        $(function(){
            var menu = {
                entity: null,
                set_entity: function(entity){
                    this.entity = entity;
                    this.form();
                    return this;
                },
                form: function(){
                    $('#name').val(this.get_name());
                    var type = this.get_type();
                    var status = $('input[name="status"]');
                    if(type == 'view'){
                        $('#site_url').val(this.get_url());
                        status.get(1).checked = true;
                    }else{
                        status.get(0).checked = true;
                    }
                    this.chang_show(status);
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
                    this.entity.find('.pre_menu_link span').text(name);
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
                    this.entity.data('url',url);
                    return this;
                },
                get_url: function(){
                    return this.entity.data('url');
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
                    if(value == 1){
                        $('.menu_content_edit').addClass('hidden');
                        $('.menu_content_url').removeClass('hidden');
                    }else{
                        $('.menu_content_edit').removeClass('hidden');
                        $('.menu_content_url').addClass('hidden');
                    }
                    return this;
                }
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
            $(document).on('click','.pre_menu_item',function(){
                var ul = $(this).parent();
                if(!$(this).hasClass('no-extra')){
                    if(!$(this).hasClass('active')){
                        ul.children('.active').removeClass('active');
                        $(this).addClass('active');
                        menu.set_entity($(this));
                    }
                }else{
                    var li = template('menuTpl');
                    $(this).before(li);
                    ul.children('.active').removeClass('active');
                    $(this).prev().addClass('active');
                    menu.set_entity($(this).prev());
                    if(ul.children().length === 4){
                        $(this).addClass('hidden');
                    }
                }
            });
            $('#name').keyup(function(){
                var value = $.trim(this.value);
                menu.set_name(value);
            });
            $('#site_url').keyup(function(){
                var value = $.trim(this.value);
                menu.set_url(value);
            });
            $('#release').click(function(){
                var buttons = [];
                var menus = $('.pre_menu_list').children();
                for(var i= 0,len=menus.length;i<len;i++){
                    if(!$(menus[i]).hasClass('no-extra')){
                        buttons[i] = {};
                        var name = $(menus[i]).data('name');
                        if(name == ''){
                            Notify('菜单名称不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                            return;
                        }
                        buttons[i].name = name;
                        buttons[i].type = $(menus[i]).data('type');
                        var url = $(menus[i]).data('url');
                        if(url == ''){
                            Notify('页面地址不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                            return;
                        }
                        buttons[i].url = url;
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