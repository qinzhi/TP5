<script src="__JS__/purchases.js"></script>
<script type="text/html" id="purchasesTpl">
    <section class="product-purchasing">
        <div class="all-shade"></div>
        <div class="product-panel">
            <div class="product-view">
                <div class="product-img">
                    <img src="{%goods_cover%}">
                </div>
                <div class="product-info">
                    <h3 class="product_name">{%goods_name%}</h3>
                </div>
            </div>
            <div class="product-select-box">
                {%if (!is_single)%}
                    {%each properties as property key%}
                        <div class="product-property-type">
                            <h3 class="property-type">{%key%}</h3>
                            <ul class="property-box">
                                {%each property as value%}
                                    <li class="property-item">{%value%}</li>
                                {%/each%}
                            </ul>
                        </div>
                    {%/each%}
                {%/if%}
                <div class="product-order flex">
                    <h3 class="order-header flex-1">购买数量：</h3>
                    <div class="order-action text-align-right">
                        <div class="amount-control">
                            <a href="javascript:;" class="amount-down pull-left"><i class="icon icon-jianhao"></i></a>
                            <input type="number" value="1" maxlength="8" pattern="d*" class="amount-input pull-left">
                            <a href="javascript:;" class="amount-up pull-left"><i class="icon icon-jiahao"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-action">
                <div class="flex">
                    <div class="flex-1"><a class="btn btn-primary no-radius btn-block product-ok">确定</a></div>
                </div>
            </div>
            <div class="panel-close">
                <i class="icon icon-cha action-close"></i>
            </div>
        </div>
    </section>';
</script>