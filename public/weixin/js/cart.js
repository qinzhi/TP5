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
        if(purchase.num > 0){
            purchase.setTips('正在加入购物车...').setNum(purchase.num + 1).updateCart();
        }else{
            purchase.create();
        }
    });
    $(document).on('click','.cart_minus',function(){
        purchase = new purchases($(this).closest('li'));
        if(purchase.num > 0){
            if(purchase.num == 1){
                purchase.hideMinus();
            }
            purchase.setTips('购物车正在处理...').setNum(purchase.num - 1).updateCart();
        }
    });
});
