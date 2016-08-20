function BrowseServer(input_image,fun )
{
    var finder = new CKFinder();
    finder.basePath = '../';
    finder.selectActionFunction = SetFileField;

    finder.selectActionData = input_image;
    if($.isFunction(fun)){
        window.fun = fun;
    }
    finder.popup();
}
function getCKeditorValue(id){
    return CKEDITOR.instances[id].getData();
}
function SetFileField( fileUrl , data )
{
    split = '\/Attachments\/';
    pic = fileUrl.split(split);
    var selectActionData = document.getElementById( (data["selectActionData"] ));
    if(!!pic[1] && selectActionData){
        selectActionData.value = pic[1];
    }
    if($.isFunction(window.fun)){
        window.fun(fileUrl,pic[1]);
    }
}
function create_category_panel(){
    var panel = $('<div id="tree_panel" class="tree_panel"></div>');
    panel.append('<ul id="tree_category" class="ztree ztree_entity"></ul>');
    $('body').append(panel);
}

$.extend({
    prefix : 'ext_',
    dialogBox : [],
    panel : {
        overlay : function(){

        }
    },
    fruiter : {
        post : function(url,data,func){
            set_loading('show');
            $.post(url,data,function(result){
                set_loading('hide');
                func(result);
            });
        },
        get : function(url,data,func){
            set_loading('show');
            $.get(url,data,function(result){
                set_loading('hide');
                func(result);
            });
        }
    },
    dialog : function(config){
        var id = config.id ? this.prefix + config.id : config.id;
        if($('#' + id).length > 0){
            return;
        }

        var async = config.async === false ? false : true;
        var title = config.title || '新窗口';
        var btnLable = config.btnLable || '保存';
        var content = '';

        var dialog = $('<div class="own_dialog"></div>');
        dialog.append('<div class="dialog_title"><h6></h6><a class="dialog_close"><i class="fa fa-times"></i></a></div>');
        dialog.append('<div class="dialog_content"><img class="dialog_loading" src="/Public/Admin/img/loading.gif"/></div>');
        dialog.append('<div class="dialog_footer"><a class="btn btn-success btn-sm shiny"></a></div>');

        if($.dialogBox.length > 1){
            var z_max_index = 0;
            $.each($.dialogBox,function(){
                var z_index = parseInt($(this).css('z-index'));
                if(z_index > z_max_index){z_max_index = z_index}
            });
            $(dialog).css('z-index',(z_max_index+1));
        }

        $('body').append(dialog);

        dialog.attr('id',id);
        dialog.find('.dialog_title h6').text(title);
        dialog.find('.dialog_footer .btn').text(btnLable);

        if($.isFunction(config.content)){
            if(async === false){
                $.ajaxSetup({
                    async : false
                });
            }
            content = config.content();
            if(async === false){
                $.ajaxSetup({
                    async : true
                });
            }
        }else{
            content = config.content;
        }

        var dialog_content = dialog.find('.dialog_content');
        if(config.min_width)
            dialog_content.css('min-width',config.min_width);
        if(config.min_height)
            dialog_content.css('min-height',config.min_height);



        dialog_content.html(content);

        dialog.set_location = function(obj){
            var h = $(obj).outerHeight();
            var w = $(obj).outerWidth();
            var dH = $(window).height();
            var dW = $(window).width();
            $(obj).css({
                left: (dW-w)/2 + 'px',
                top: ((dH-h)/2 -50) + 'px'
            });
        }(dialog);

        this.dialogBox.push(dialog);

        $(dialog).data('isMove',true);

        $(dialog).bind({
            mousedown: function(event){

                if($.dialogBox.length > 1){
                    var z_max_index = 0;
                    $.each($.dialogBox,function(){
                        var z_index = parseInt($(this).css('z-index'));
                        if(z_index > z_max_index){z_max_index = z_index}
                    });
                    $(this).css('z-index',(z_max_index+1));
                }

                if(!$(event.target).hasClass('own_dialog') && !$(event.target).hasClass('dialog_title')){
                    return;
                }

                $(dialog).data('isMove',true);

                var abs_x = event.pageX - $(this).offset().left;
                var abs_y = event.pageY - $(this).offset().top;
                var obj = $(this);

                $('body').attr({
                    'style' : '-moz-user-select:none',
                    'unselectable' : 'on',
                    'onselectstart' : 'return false'
                });
                /*var w = $(this).width();
                 var h = $(this).height();

                 var pw = $(document).width();
                 var ph = $(document).height();*/


                $(document).mousemove(function(event){

                    var mX = event.pageX - abs_x;
                    var mY = event.pageY - abs_y;

                    if($(obj).data('isMove') == true /* && mX > 0 && mY > 0 && mX < (pw-w) && mY < (ph-h)*/){
                        obj.css({'left':event.pageX - abs_x, 'top':event.pageY - abs_y});
                    }
                });
            },
            mouseup: function(event){
                $(dialog).data('isMove',false);
                $('body').removeAttr('style').removeAttr('unselectable').removeAttr('onselectstart');
            }
        }).find('.dialog_close').click(function(){
            var dialog_id = $(this).parents('.own_dialog').attr('id'),id='';
            for(var i= ($.dialogBox.length-1);i>=0;i--){
                id = $.dialogBox[i].attr('id');
                $.dialogBox[i].remove();
                $.dialogBox.pop();
                if(id == dialog_id){
                    break;
                }
            }
        });
        $(dialog).find('.dialog_footer a').click(function(){
            if($.isFunction(config.ok)){
                if(async === false){
                    $.ajaxSetup({
                        async : false
                    });
                }
                var result = config.ok(dialog);
                if(async === false){
                    $.ajaxSetup({
                        async : true
                    });
                }
                if(result === false){
                    return;
                }
            }
            $(this).parents('.own_dialog').find('.dialog_close').trigger('click');
        });
    },
    regex: function(pattern){
        switch(pattern)
        {
            case 'required': pattern = /\S+/i;break;
            case 'email': pattern = /^\w+([-+.]\w+)*@\w+([-.]\w+)+$/i;break;
            case 'qq':  pattern = /^[1-9][0-9]{4,}$/i;break;
            case 'id': pattern = /^\d{15}(\d{2}[0-9x])?$/i;break;
            case 'ip': pattern = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/i;break;
            case 'zip': pattern = /^\d{6}$/i;break;
            case 'mobi': pattern = /^1[3|4|5|7|8][0-9]\d{8}$/;break;
            case 'phone': pattern = /^((\d{3,4})|\d{3,4}-)?\d{3,8}(-\d+)*$/i;break;
            case 'url': pattern = /^[a-zA-z]+:\/\/(\w+(-\w+)*)(\.(\w+(-\w+)*))+(\/?\S*)?$/i;break;
            case 'date': pattern = /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29)$/i;break;
            case 'datetime': pattern = /^(?:(?!0000)[0-9]{4}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-8])|(?:0[13-9]|1[0-2])-(?:29|30)|(?:0[13578]|1[02])-31)|(?:[0-9]{2}(?:0[48]|[2468][048]|[13579][26])|(?:0[48]|[2468][048]|[13579][26])00)-02-29) (?:(?:[0-1][0-9])|(?:2[0-3])):(?:[0-5][0-9]):(?:[0-5][0-9])$/i;break;
            case 'int':	pattern = /^\d+$/i;break;
            case 'float': pattern = /^\d+\.?\d*$/i;break;
            default : pattern = null;break;
        }
        return pattern;
    },
    validateOnChange : function(obj){
        var pattern = obj.getAttribute('pattern');
        if(pattern != ''){
            pattern = this.regex(pattern);
            if(pattern && !pattern.test(obj.value)){
                if(!$(obj).parent().hasClass('has-error')){
                    $(obj).parent().addClass('has-error');
                }
                obj.focus();
                return false;
            }
        }
        return true;
    },
    validateOnSubmit : function(form){
        for(var i = 0;i<form.elements.length;i++){
            var e = form.elements[i];
            var pattern = e.getAttribute('pattern');
            if(pattern != ''){
                pattern = this.regex(pattern);
                if(pattern && !pattern.test(e.value)){
                    if(!$(e).parent().hasClass('has-error')){
                        $(e).parent().addClass('has-error');
                    }
                    e.focus();
                    return false;
                }
            }
        }
        return true;
    }
});