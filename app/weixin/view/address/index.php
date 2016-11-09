{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/address.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-address" id="page-address_list">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="{:url('member/person')}">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">收货地址</h1>
    </header>
    <div class="bar bar-footer">
        <a class="button button-fill btn-add" href="{:url('address/add')}">新增收货地址</a>
    </div>
    <div class="content">
        {volist name="addressList" id="vo"}
            <div class="card" data-id="{$vo.id}">
                <div class="card-content">
                    <div class="card-content-inner">
                        <p>
                            <span class="consignee">{$vo.consignee}</span>
                            <span class="mobile">{$vo.mobile}</span>
                        </p>
                        <p class="area_info">{$vo.area_info}</p>
                        <label class="label-checkbox">
                            <input name="chk-id" value="{$vo.id}" autocomplete="off" {$vo.is_default?'checked':''} type="radio">
                            <div class="item-media">
                                <i class="icon icon-form-checkbox"></i>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="flex-1"><a class="btn-card_primary cart_add" href="{:url('address/edit',['id'=>$vo['id']])}">编辑</a></div>
                    <div class="flex-1"><a class="btn-card_danger btn-del" href="javascript:;">删除</a></div>
                </div>
            </div>
        {/volist}
    </div>
</div>
{/block}
{block name="js"}
<script type="application/javascript">
    $(function () {
        $('.btn-del').click(function () {
            var self = this;
            $.confirm('确认是否删除?', function () {
                var card = $(self).closest('.card');
                var id = card.data('id');
                $.showIndicator();
                $.post('{:url("address/del")}',{address_id:id},function (result) {
                    $.hideIndicator();
                    $.toast(result.msg);
                    if(result.code == 1){
                        card.remove();
                    }
                });
            });
            var card = $(this).closest('.card');
        });
        $('.card-content').click(function () {
            var chk = $(this).find('input[name="chk-id"]').get(0);
            if(chk.checked === false){
                chk.checked = true;
                $(chk).trigger('change');
            }
        });
        $('input[name="chk-id"]').change(function () {
            var id = this.value;
            $.showIndicator();
            $.post("{:url('address/setDefault')}",{id:id},function (result) {
                $.hideIndicator();
            });
        });
    });
</script>
{/block}