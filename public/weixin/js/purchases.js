function purchases(goods){
    var entity = this;
    this.goods = goods;
    this.input = goods.find('.cart_num');
    this.win_height = $(window).height();
    this.action = 'up';
    this.sku = parseInt(this.goods.data('sku'));
    this.unit = this.goods.data('unit');
    this.num = parseInt(this.goods.data('cart_num')) || 1;
    this.goods_id = this.goods.data('id');
    this.goods_cover = this.goods.find('.product-img img').attr('src');
    this.goods_name = this.goods.find('.product_name').text();
    this.properties = null;
    this.products = null;
    this.is_single = 0;
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
            this.input.val(this.num);
        }
        return this;
    };
    this.getNum = function(){
        return this.num;
    };
    this.updateCart = function(){
        $.showPreloader(this.tips);
        $.post('/weixin/cart/add',{goods_id:this.goods_id,num:this.num},function(result){
            if(result.code != 1){
                $.toast(result.msg,2000, 'top-80');
            }
            entity.goods.attr('data-cart_num',result.num);
            entity.input.val(result.num);
            $('#cart_num').text(result.totalNum);
            $.hidePreloader();
        });
        return this;
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
                console.log(this.properties);

                var entity = this;
                var render = template.compile(entity.source);
                var html = render({
                    goods_cover:this.goods_cover,
                    goods_name:this.goods_name,
                    is_single:this.is_single,
                    properties:this.properties
                });

                var page = entity.goods.closest('.page');
                page.append(html);
                entity.show();

                entity.section = page.find('.product-purchasing');
                entity.shade = entity.section.find('.all-shade');
                entity.panel = entity.section.find('.product-panel');

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
                    entity.close()
                    if(entity.num > 0){
                        entity.updateCart();
                    }
                });

                entity.section.find('.action-close').click(function(){
                    entity.close();
                });
            }
        });
    };

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
$(function(){
    var purchase = null;
    $(window).resize(function(e){
        if(!!purchase){
            var cur_win_height = $(this).height();
            if(purchase.panel.length >= 1 && purchase.win_height > cur_win_height){
                purchase.setHeight('100%');
            }else{
                purchase.setHeight('80%');
            }
        }
    });

    $(document).on('blur','.cart_num',function(){
        var num = parseInt(this.value);
        purchase = new purchases($(this).closest('li'));
        purchase.setTips('购物车正在处理...').setNum(num).updateCart();
    });
    $(document).on('click','.cart_add',function(){
        purchase = new purchases($(this).closest('li'));
        /*if(purchase.num > 0){
         purchase.setTips('正在加入购物车...').setNum(purchase.num + 1).updateCart();
         }else{
         purchase.create();
         }*/
        purchase.create();
    });
    $(document).on('click','.cart_minus',function(){
        purchase = new purchases($(this).closest('li'));
        if(purchase.num > 0){
            if(purchase.num == 1){
                //purchase.hideMinus();
            }
            purchase.setTips('购物车正在处理...').setNum(purchase.num - 1).updateCart();
        }
    });
});