function purchases(goods){
    var entity = this;
    this.goods = goods;
    this.addBtn = goods.find('.cart_add');
    this.minusBtn = goods.find('.cart_minus');
    this.input = goods.find('.cart_num');
    this.win_height = $(window).height();
    this.action = 'up';
    this.sku = this.goods.data('sku');
    this.unit = this.goods.data('unit');
    this.num = this.goods.data('cart_num');
    this.goods_id = this.goods.data('id');
    this.goods_cover = this.goods.find('.product-img img').attr('src');
    this.goods_name = this.goods.find('.product_name').text();
    this.section = null;
    this.panel = null;
    this.shade = null;
    this.tips = '正在加入购物车...';
    this.setTips = function(tips){
        this.tips = tips;
        return this;
    }
    this.setNum = function(num){
        if(num < 0){
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
        $.post('/cart/add',{goods_id:this.goods_id,num:this.num},function(result){
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
    this.hideMinus = function(){
        this.minusBtn.hide();
        this.input.hide();
    };
    this.showMinus = function(){
        this.minusBtn.show();
        this.input.show();
    }
    this.setHeight = function(height){
        this.panel.css('height',height);
        return this;
    };
    this.source = '<section class="product-purchasing">' +
                        '<div class="all-shade "></div>' +
                        '<div class="product-panel">' +
                            '<div class="product-view">' +
                                '<div class="product-img">' +
                                    '<img src="' + this.goods_cover + '">' +
                                '</div>' +
                                '<div class="product-info">' +
                                    '<h3 class="product_name">' + this.goods_name + '</h3>' +
                                    '<div class="flex widthScale00 rule">' +
                                        '{{each rules as rule index}}' +
                                            '<div class="flex-1">' +
                                                '<p class="product_price">' +
                                                    '<sup>￥</sup><em>{{rule.price[0]}}.</em><i>{{rule.price[1]}}</i>' +
                                                '</p>' +
                                                '<p class="product-purchase_num">{{rule.numLabel}}<unit>' + this.unit + '</unit></p>' +
                                            '</div>' +
                                        '{{/each}}' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="product-order">' +
                                '<div class="order-header">' +
                                    '<span class="order-title">购买数量：</span>' +
                                    '<span class="order-stock">库存' +
                                        '<span class="value">' + this.sku + '</span>' +
                                        '<span class="unit">' + this.unit + '</span>' +
                                    '</span>' +
                                '</div>' +
                                '<div class="order-action">' +
                                    '<div class="amount-control">' +
                                        '<a href="javascript:;" class="amount-down pull-left">' +
                                            '<i class="icon icon-jianhao"></i>' +
                                        '</a>' +
                                        '<input type="number" value="' + this.num + '" maxlength="8" pattern="d*" class="amount-input pull-left">' +
                                        '<a href="javascript:;" class="amount-up pull-left">' +
                                            '<i class="icon icon-jiahao"></i>' +
                                        '</a>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="product-action">' +
                                '<div class="flex">' +
                                    '<div class="flex-1"><a class="btn btn-primary no-radius btn-block product-ok">确定</a></div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="panel-close">' +
                                '<i class="icon icon-cha action-close"></i>' +
                            '</div>' +
                        '</div>' +
                    '</section>';
    this.create = function(){

        var entity = this;

        var rules = JSON.parse(entity.goods.data('rule'));
        for(var i in rules){
            rules[i].price = rules[i].price.split('.');
            rules[i].numLabel = '≥' + rules[i].num;
        }

        var render = template.compile(entity.source);
        var html = render({rules:rules});

        var page = entity.goods.closest('.page');
        page.append(html);
        entity.show();

        entity.section = page.find('.product-purchasing');
        entity.shade = entity.section.find('.all-shade');
        entity.panel = entity.section.find('.product-panel');

        var input = entity.section.find('.amount-input');

        entity.section.find('.amount-down').click(function(){
            input.val(entity.num - 1);
            entity.setNum(entity.num - 1);
        });
        entity.section.find('.amount-up').click(function(){
            input.val(entity.num + 1);
            entity.setNum(entity.num + 1);
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
                entity.updateCart().showMinus();
            }
        });

        entity.section.find('.action-close').click(function(){
            entity.close();
        });
    };

    this.close = function(){
        this.shade.removeClass('fade_toggle');
        this.panel.removeClass('active');
        var section = this.section;
        setTimeout(function(){
            section.remove();
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
