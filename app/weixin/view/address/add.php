{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/address.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-address" id="page-address_save">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="{:url('address/index')}">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">收货地址</h1>
    </header>
    <div class="content">
        <form method="post" id="form-save">
            <div class="list-block">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input name="consignee" placeholder="收货人" maxlength="8" type="text">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input name="mobile" placeholder="收货人手机号" maxlength="11" type="tel">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input name="area" id="city-picker" placeholder="省、市、区/县" readonly="" type="text">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <textarea name="address" placeholder="详细地址" maxlength="126"></textarea>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="address-default">
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">设为默认地址</div>
                                <div class="item-input">
                                    <label class="label-switch">
                                        <input name="is_default" value="1" type="checkbox" class="hidden">
                                        <div class="checkbox"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="content-block">
                <button type="submit" class="button button-fill button-warning button-medium">保存</button>
            </div>
        </form>
    </div>
</div>
{/block}
{block name="quote-js"}
<script type="text/javascript" src="__LIGHT7__/js/light7-city-picker.min.js" charset="utf-8"></script>
{/block}
{block name="js"}
<script type="application/javascript">
    $(function () {
        $('#city-picker').cityPicker({
            toolbarTemplate: '<header class="bar bar-nav"><h1 class="title">选择地区</h1><button class="button button-link pull-right close-picker">确定</button></header>',
            cssClass: 'address-picker city-picker'
        });
        $('#form-save').submit(function () {
            var consignee = $.trim(this.consignee.value);
            if(consignee == ''){
                this.consignee.focus();
                $.toast('收货人不能为空');
                return false;
            }
            var mobile = $.trim(this.mobile.value);
            var pattern = $.regex('mobi');
            if(pattern.test(mobile) === false){
                this.mobile.focus();
                $.toast('手机号格式不正确');
                return false;
            }
            var area = $.trim(this.area.value);
            if(area == ''){
                this.area.focus();
                $.toast('请选择省、市、区/县');
                return false;
            }
            var address = $.trim(this.address.value);
            if(address == ''){
                this.address.focus();
                $.toast('详细地址不能为空');
                return false;
            }
        });
    });
</script>
{/block}