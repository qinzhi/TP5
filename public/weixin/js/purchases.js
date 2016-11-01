function purchases(goods){
    var entity = this;
    this.goods = goods;
    this.win_height = $(window).height();
    this.action = 'up';
    this.sku = parseInt(this.goods.data('sku'));
    this.unit = this.goods.data('unit');
    this.num = parseInt(this.goods.data('cart_num')) || 1;
    this.goods_id = this.goods.data('id');
    this.goods_cover = this.goods.find('.product-img img').attr('src');
    this.goods_name = this.goods.find('.product_name').text();
    this.goods_price = this.goods.data('price');
    this.properties = null;
    this.products = null;
    this.is_single = 0;
    this.product_id = 0;
    this.section = null;
    this.panel = null;
    this.shade = null;
    this.tips = '正在加入购物车...';
    this.setTips = function(tips){
        this.tips = tips;
        return this;
    }
    this.setNum = function(num){
        if(num <= 0){
            $.toast('数量必须大于0',2000, 'top-80');
        }else if(num > this.sku){
            $.toast('添加数量已上限',2000, 'top-80');
        }else{
            this.num = num;
        }
        return this;
    };
    this.getNum = function(){
        return this.num;
    };
    this.updateCart = function(){
        if(this.product_id){
            $.showPreloader(this.tips);
            $.post('/weixin/cart/add',{product_id:this.product_id,num:this.num},function(result){
                $.hidePreloader();
                $.toast(result.msg,2000, 'top-80');
                if(result.code == 1){
                    entity.goods.attr('data-cart_num',result.num);
                    $('#cart_num').text(result.totalNum);
                }
            });
            return this;
        }else{
            this.exception();
            return false;
        }
    };

    this.setHeight = function(height){
        this.panel.css('height',height);
        return this;
    };
    this.source = document.getElementById('purchasesTpl').innerHTML;

    this.create = function(){
        $.ajax({
            url: '/weixin/goods/getProductList',
            type: 'post',
            data: {goods_id:this.goods_id},
            context: this,
            success: function(result){

                this.is_single = result['is_single'];
                this.properties = result['properties'];
                this.products = result['products'];

                var entity = this;
                var render = template.compile(entity.source);
                var html = render({
                    goods_cover:this.goods_cover,
                    goods_name:this.goods_name,
                    goods_price:this.goods_price,
                    is_single:this.is_single,
                    properties:this.properties
                });

                var page = entity.goods.closest('.page');
                page.append(html);

                entity.show();

                entity.section = page.find('.product-purchasing');
                entity.section.find('.all-shade').click(function () {
                    entity.close();
                });
                entity.shade = entity.section.find('.all-shade');
                entity.panel = entity.section.find('.product-panel');
                
                if(this.is_single){
                    this.product_id = this.products[0].id;
                }else{
                    entity.panel.find('.property-item').bind('click',function () {
                        if(!$(this).hasClass('selected') && !$(this).hasClass('disabled')){
                            var index = $(this).closest('.product-property-type').index();
                            var val = $(this).text();
                            var box = [];
                            $(entity.products).each(function (i) {
                                var spec = JSON.parse(this['spec_array']);
                                if(this['store_nums'] > 0 && spec[index].value == val){
                                    for(var i in spec){
                                        if(i != index){
                                            box[i] = box[i] || [];
                                            box[i].name = spec[i].name;
                                            box[i].value = box[i].value || [];
                                            box[i].value.push(spec[i].value);
                                        }
                                    }
                                }
                            });
                            if(box){
                                var property_type = entity.panel.find('.product-property-type');
                                $.each(box,function (i) {
                                    if(box[i]){
                                        var items = $(property_type[i]).find('.property-item');
                                        $.each(items,function (k) {
                                            if(box[i].value.indexOf(items[k].innerHTML) === -1){
                                                $(items[k]).removeClass('selected').addClass('disabled');
                                            }else{
                                                $(items[k]).removeClass('disabled');
                                            }
                                        });
                                    }
                                });
                            }
                            $(this).parent().find('.selected').removeClass('selected');
                            $(this).addClass('selected');
                            entity.showSelect().setProductId().chagePirce();
                        }
                    });
                }
                

                var input = entity.section.find('.amount-input');

                entity.section.find('.amount-down').click(function(){
                    input.val(entity.setNum(entity.num - 1).getNum());
                });
                entity.section.find('.amount-up').click(function(){
                    input.val(entity.setNum(entity.num + 1).getNum());
                });
                input.on({
                    blur: function(){
                        var num = parseInt(this.value);
                        if(num <= 0){
                            num = this.value = 1;
                        }else if(num > entity.sku){
                            num = this.value = entity.sku;
                        }
                        entity.setHeight('80%').setNum(num);
                    },
                    focus: function(){
                        entity.setHeight('100%');
                    }
                });

                entity.section.find('.product-ok').click(function(){
                    if(entity.num > 0){
                        if(entity.updateCart()){
                            entity.close()
                        }
                    }
                });

                entity.section.find('.action-close').click(function(){
                    entity.close();
                });
            }
        });
    };
    this.chagePirce = function () {
        if(!this.is_single && this.product_id > 0){
            for(var i=0;i<this.products.length;i++){
                if(this.products[i].id == this.product_id){
                    this.panel.find('.product_price').text(this.products[i].sell_price);
                    return;
                }
            }
        }
    };
    this.showSelect = function () {
        if(!this.is_single){
            var property = this.panel.find('.product_property');
            property.html('已选择 ');
            this.panel.find('.property-item.selected').each(function () {
                property.html(property.html() + $(this).text() + ' ');
            });
        }
        return this;
    };
    this.setProductId = function () {
        if(!this.is_single){
            var spec = this.getSelectSpec();
            if(spec !== false){
                var flag = true;
                for(var i=0;i<this.products.length;i++){
                    var product = this.products[i];
                    var data = JSON.parse(product['spec_array']);
                    flag = true;
                    $.each(data,function () {
                        if(spec.indexOf(this.value) === -1) flag = false;
                    });
                    if(flag) break;
                }
                this.product_id = flag ? this.products[i].id : 0;
            }else{
                this.product_id = 0;
            }
        }
        return this;
    };
    this.getProductId = function () {
        return this.product_id || 0;
    };

    this.exception = function () {
        var property_type = this.panel.find('.product-property-type');
        var msg = '请选择';
        $.each(property_type,function (i) {
            var item = $(this).find('.property-item.selected');
            var type = $(this).find('.property-type').text();
            if(!item.length){
                msg += ' <span>' + type + '</span>';
            }
        });
        $.toast(msg);
    }

    this.getSelectSpec = function () {
        var property_type = this.panel.find('.product-property-type');
        var spec = [];
        $.each(property_type,function (i) {
            var item = $(this).find('.property-item.selected');
            var text = item.text();
            if(item.length){
                spec.push(text);
            }
        });
        return property_type.length == spec.length ? spec : false;
    }

    this.close = function(){
        this.shade.removeClass('fade_toggle');
        this.panel.removeClass('active');
        //var section = this.section;
        setTimeout(function(){
            entity.section.remove();
        },300);
        delete this;
    };

    this.show = function(){
        var entity = this;
        setTimeout(function(){
            entity.shade.addClass('fade_toggle');
            entity.panel.addClass('active');
        },10);
        return this;
    };
}