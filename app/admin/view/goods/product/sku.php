<form class="padding-10">
    <table class="table table-bordered table-hover table-border">
        <thead>
        <tr>
            <th width="70%">商品</th>
            <th width="30%">库存量</th>
        </tr>
        </thead>
        <tbody>
            <input type="hidden" name="action" value="sku"/>
            <input type="hidden" name="goods_id" value="{$_POST.id}"/>
            {volist name="products" id="vo"}
                <tr>
                    <input type="hidden" name="_product_id[]" value="{$vo.id}"/>
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
                            <input type="text" maxlength="10" pattern="float" name="_store_nums[]" value="{$vo.store_nums}" class="form-control input-xs">
                        </div>
                    </td>
                </tr>
            {/volist}
        </tbody>
    </table>
</form>