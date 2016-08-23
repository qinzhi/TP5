<form class="padding-10">
    <table class="table table-bordered table-hover table-border">
        <thead>
        <tr>
            <th width="40%">商品</th>
            <th width="20%">市场价格</th>
            <th width="20%">销售价格</th>
            <th width="20%">成本价格</th>
        </tr>
        </thead>
        <tbody>
            <input type="hidden" name="action" value="price"/>
            <input type="hidden" name="goods_id" value="{$_POST.id}"/>
            {volist name="products" id="vo"}
                <tr>
                    <input type="hidden" name="_product_id[]" value="{$vo.id}"/>
                    <if condition="$vo['is_default'] eq 1">
                        <input type="hidden" name="_default" value="{$key}"/>
                    </if>
                    <td {$vo['is_default']?'class="success"':''}>
                        {$goods.name}
                        <?php
                            $spec_array = json_decode($vo['spec_array']);
                            foreach($spec_array as $val){
                                echo $val->value . "\n";
                            }
                        ?>
                    </td>
                    <td>
                        <div class="form-group has-feedback no-margin">
                            <input type="text" maxlength="10" pattern="float" name="_market_price[]" value="{$vo.market_price}" class="form-control input-xs">
                        </div>
                    </td>
                    <td>
                        <div class="form-group has-feedback no-margin">
                            <input type="text" maxlength="10" pattern="float" name="_sell_price[]" value="{$vo.sell_price}" class="form-control input-xs">
                        </div>
                    </td>
                    <td>
                        <div class="form-group has-feedback no-margin">
                            <input type="text" maxlength="10" pattern="float" name="_cost_price[]" value="{$vo.cost_price}" class="form-control input-xs">
                        </div>
                    </td>
                </tr>
            {/volist}
        </tbody>
    </table>
</form>