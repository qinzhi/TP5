var GoodsImage = function(config){
    config = config || {};
    this.id = config.id || 'add-image';
    this.limit = config.limit || 5;
    this.data = config.data || {};//初始数据
    this.setLabel = config.setLabel || '设为封面图';
    this.label = config.label || '封面图片';
    this.successLable = config.successLable || '设置成功';
    this.deleteLable = config.deleteLable || '删除成功';
    this.confirmLable = config.confirmLable || '确定要删除吗?';
    this.ul = $('#' + this.id).parent();
    window[this.id] = this;
    document.getElementById(this.id).onclick = function(e){
        var id = $(this).attr('id');
        var me = $(this);
        var $_ = window[id];
        BrowseServer('',function(fileUrl,saveUrl){
            if($_.getLen() > $_.limit){
                Notify('最多添加'+$_.limit+'条', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                return;
            }
            $_.addImage(fileUrl,saveUrl,$_);
        });
    };
    this.addImage = function(fileUrl,saveUrl,$_){
        var coverTpl = template('coverTpl',{img:fileUrl,image:saveUrl,index:$_.getLen()});
        var li = $('#' + $_.id).before(coverTpl).prev();
        li.find('.delete').bind('click',$_,$_.delete);
        li.find('.set-cover').bind('click',$_,$_.setCover);
    };
    this.setCover = function(e,extra){
        var target = e.data;
        var li = $(this).closest('li');
        if(li.find('#cover-index').length <= 0){
            var cover_li = target.ul.find('#cover-index').closest('li');
            cover_li.find('.set-cover').text(target.setLabel).removeClass('active');
            target.ul.find('#cover-index').remove();
            li.append('<input type="hidden" id="cover-index" name="cover_index" value="' + li.index() + '"/>');
            $(this).text(target.label).addClass('active');
            if(extra && extra.noTip === true);else Notify(target.successLable, 'bottom-right', '5000', 'success', 'fa-check', true);
        }
    };
    this.delete = function(e){
        var target = e.data;
        var li = $(this).closest('li');
        var index = $(this).index();
        bootbox.confirm(target.confirmLable, function (result) {
            if (result) {
                if(li.find('#cover-index').length > 0){
                    var first_li = target.ul.find('li.goods-img:eq(0)');
                    if(first_li.length){
                        first_li.append('<input type="hidden" id="cover-index" name="cover_index" value="0"/>');
                        first_li.find('.set-cover').text(target.label).addClass('active');
                    }
                }
                li.remove();
                Notify(target.deleteLable, 'bottom-right', '5000', 'success', 'fa-check', true);
            }
        });
    };
    this.getLen = function(){
        return this.ul.find('.goods-img').length;
    };
    if(this.data.length > 0){
        for(var i in this.data){
            var fileUrl = this.data[i].imageUrl;
            var saveUrl = this.data[i].image;
            this.addImage(fileUrl,saveUrl,this);
            if(this.data[i].is_default == 1){
                this.ul.find('li:eq('+i+')').find('.set-cover').trigger('click',{noTip: true});
            }
        }
    }
};