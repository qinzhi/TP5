{extend name="layout/base" /}
{block name="css"}
<link href="__CSS__/weixin.css" rel="stylesheet" />
{/block}
{block name="content"}
<div class="row no-margin">
    <div class="menu_action">
        <a class="btn btn-success" id="release">保 存</a>
    </div>
    <div class="reply_area">
        <div class="reply_content">
            <div class="reply_edit col-sm-12">
                <ul class="tab_navs">
                    <li class="tab_nav tab_text">
                        <a href="javascript:void(0);">
                            <i class="icon_msg_sender"></i>
                            <span class="msg_tab_title">文字</span>
                        </a>
                    </li>
                    <li class="tab_nav tab_news active">
                        <a href="javascript:void(0);">
                            <i class="icon_msg_sender"></i>
                            <span class="msg_tab_title">图文消息</span>
                        </a>
                    </li>
                    <li class="tab_nav tab_img">
                        <a href="javascript:void(0);">
                            <i class="icon_msg_sender"></i>
                            <span class="msg_tab_title">图片</span>
                        </a>
                    </li>
                    <li class="tab_nav tab_audio">
                        <a href="javascript:void(0);">
                            <i class="icon_msg_sender"></i>
                            <span class="msg_tab_title">语音</span>
                        </a>
                    </li>
                    <li class="tab_nav tab_video">
                        <a href="javascript:void(0);">
                            <i class="icon_msg_sender"></i>
                            <span class="msg_tab_title">视频</span>
                        </a>
                    </li>
                </ul>
                <div class="tabs_content">
                    <div class="tab_type tab_type_text" style="display: none;">
                        <textarea class="text_txt" id="text_txt" autocomplete="off"></textarea>
                        <div class="footer_toolbar">
                            <a class="icon_emotion" href="javascript:void(0);">表情</a>
                            <p class="text_tip">还可以输入<em data-len="600">600</em>字</p>
                            <div class="emotion_area hidden">
                                <ul class="emotions">
                                    {volist name="emotions" id="vo"}
                                    <li class="emotions_item">
                                        <i class="emotion_i" style="background-position:-{$key * 24}px 0;"
                                           data-title="{$vo.title}" data-gifurl="{$vo.gifurl}"></i>
                                    </li>
                                    {/volist}
                                </ul>
                                <span class="emotions_preview"></span>
                            </div>
                        </div>
                    </div>
                    <div class="tab_type tab_type_news" style="display: block;">
                        <div class="media_cover" id="select-news">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>从素材库中选择</strong>
                                </a>
                            </span>
                        </div>
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>新建图文消息</strong>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="tab_type tab_type_img" style="display: none;">
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>从素材库中选择</strong>
                                </a>
                            </span>
                        </div>
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>上传图片</strong>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="tab_type tab_type_audio" style="display: none;">
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>从素材库中选择</strong>
                                </a>
                            </span>
                        </div>
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>新建语音</strong>
                                </a>
                            </span>
                        </div>
                    </div>
                    <div class="tab_type tab_type_video" style="display: none;">
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>从素材库中选择</strong>
                                </a>
                            </span>
                        </div>
                        <div class="media_cover">
                            <span class="create_access">
                                <a class="add_gray_wrp" href="javascript:;">
                                    <i class="icon-add">+</i>
                                    <strong>新建视频</strong>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="js"}
<script>
    $(function () {
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
        $('#text_txt').keyup(function () {
            var len = $(this).val().trim().length;
            var mlen = parseInt($('.text_tip em').data('len'));
            $('.text_tip em').text(mlen-len);
            if(len > mlen){
                this.value = this.value.substr(0,mlen);
                $('.text_tip em').text(0);
            }
        });
        $('.tab_navs .tab_nav').click(function () {
            if(!$(this).hasClass('active')){
                $(this).parent().find('.tab_nav.active').removeClass('active');
                var index = $(this).index();
                console.log(index);
                $(this).addClass('active');
                var tab_type = $('.tab_type');
                $(tab_type.hide().get(index)).show();
            }
        });
        $('#select-news').click(function(){
            $.dialog({
                id : 'selectNews',
                title : '选择素材',
                async : false,
                min_width: 600,
                min_height: 350,
                auto_close: false,
                content : function(){
                    var content = '';
                    $.post("{:url('weixin/lib_news')}",function(data){
                        content = data;
                    });
                    return content;
                },
                ok : function(target){
                    target.close();//关闭弹窗
                }
            });
        });
    });
</script>
{/block}