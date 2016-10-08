$(document).on("pageInit", "#page-cart", function(e, id, page) {
    var selectAll =page.find('#selectAll');
    var selectSon = page.find('input[name="chk-id"]');
    selectAll.change(function () {
        $.ajax({
            type: 'post',
            url: '/weixin/cart/setSelected',
            data: {cart_id: 0,is_selected: this.checked?1:0},
            context: this,
            beforeSend: function () {
                $.showIndicator();
            },
            success: function (result) {
                if(result.code == 1){
                    if(this.checked == true){
                        selectSon.each(function () {
                            this.checked = true;
                        });
                    }else{
                        selectSon.each(function () {
                            this.checked = false;
                        });
                    }
                    Cart.statistics();
                }else{
                    this.checked = !this.checked;
                    $.alert(result.msg);
                }
            },
            complete: function () {
                $.hideIndicator()
            }
        });
        
    });
    selectSon.change(function () {
        $.ajax({
            type: 'post',
            url: '/weixin/cart/setSelected',
            data: {cart_id: this.value,is_selected: this.checked?1:0},
            context: this,
            beforeSend: function () {
                $.showIndicator();
            },
            success: function (result) {
                if(result.code == 1){
                    if(this.checked){
                        var status = true;
                        selectSon.each(function () {
                            if(this.checked === false) status = false;
                        });
                        if(status) selectAll.get(0).checked = true;
                    }else{
                        selectAll.get(0).checked = false;
                    }
                    Cart.statistics();
                }else{
                    this.checked = !this.checked;
                    $.alert(result.msg);
                }
            },
            complete: function () {
                $.hideIndicator()
            }
        });
    });
    var Cart = {
        widget: {
            add: page.find('.cart_add'),
            minus: page.find('.cart_minus'),
            input: page.find('.cart_num'),
            clearing: page.find('.btn-clearing'),
            ul: page.find('.cart-list')
        },
        product_id: 0,
        index: 0,
        sku: 0,
        input: null,
        li: null,
        quantity: page.find('.quantity'),
        cash: page.find('.cash'),
        add: function (e) {
            var num = this.reInit(e.target).getNum() + 1;
            if(isNaN(num) || num > this.sku){
                $.toast('库存仅剩' + this.sku + this.unit);
            }else{
                this.update(num);
            }
        },
        minus: function (e) {
            var num = this.reInit(e.target).getNum() - 1;
            if(!isNaN(num) && num > 0){
                this.update(num);
            }
        },
        update: function (num) {
            $.showIndicator();
            var self = this;
            $.post('/weixin/cart/update',{product_id:this.product_id,num:num},function(result){
                $.hideIndicator();
                if(result.code == 1){
                    self.setNum(result.num).statistics();
                }else{
                    if(result.code == -2) self.setNum(result.num);
                    $.toast(result.msg,2000, 'top-80');
                }
            });
        },
        getNum: function () {
            return parseInt(this.input.val());
        },
        setNum: function (num) {
            this.input.val(num);
            return this;
        },
        clearing: function (e) {
            var cart_id = [];
            this.widget.ul.find('input[type="checkbox"]:checked').each(function () {
                cart_id.push(this.value);
            });
            if(cart_id.length){
                $.setCookie('cart_id',JSON.stringify(cart_id));
                window.location.href = '/weixin/order/create';
            }
        },
        statistics: function () {
            var total_num = 0,total_price = 0;
            this.widget.ul.children().each(function () {
                var checked = $(this).find('input[name="chk-id"]').get(0).checked;
                if(checked){
                    var num = parseInt($(this).find('input[name="cart_num"]').val());
                    var price = parseFloat($(this).data('price'));
                    total_num += num;
                    total_price += (num * price);
                }
            });
            this.quantity.text(total_num);
            this.cash.text(total_price);
            return this;
        },
        reInit: function (e) {
            this.li = $(e).parents('li');
            this.unit = this.li.data('unit');
            this.sku = this.li.data('sku');
            this.product_id = this.li.data('product_id');
            this.input = this.li.find('.cart_num');
            return this;
        },
        init: function () {
            var self = this;
            this.widget.add.bind('click',function (e) {
                self.add(e);
            });
            this.widget.minus.bind('click',function (e) {
                self.minus(e);
            });
            this.widget.clearing.bind('click',function (e) {
                self.clearing(e);
            });
            var chk_len = this.widget.ul.find('li input[name="chk-id"]:checked').length;
            var len = this.widget.ul.find('li input[name="chk-id"]').length;
            if(chk_len == len)
                selectAll.attr('checked',true);
            return this.statistics();
        }
    }.init();
});
