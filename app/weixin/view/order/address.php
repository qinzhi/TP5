<section class="order-address_add">
    <div class="all-shade"></div>
    <div class="address-panel">
        <header class="address-header"><h4>编辑收货地址</h4></header>
        <form class="address-area-form list-block" autocomplete="off">
            <ul>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <input type="text" name="consignee" placeholder="收货人" maxlength="8">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <input type="tel" name="mobile" placeholder="收货人手机号" maxlength="11">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <input type="text" name="area" id="city-picker" placeholder="省、市、区/县">
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
                            <div class="item-title label">默认地址</div>
                            <div class="item-input">
                                <label class="label-switch">
                                    <input type="checkbox" name="default">
                                    <div class="checkbox"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </form>
        <div class="address-action">
            <div class="flex">
                <div class="flex-1">
                    <a class="btn btn-primary no-radius btn-block address-ok">保存</a>
                </div>
            </div>
        </div>
        <div class="panel-close">
            <i class="icon icon-cha action-close"></i>
        </div>
    </div>
</section>
<section class="order-address_select">
    <div class="all-shade"></div>
    <div class="address-panel">
        <header class="address-header"><h4>选择收货地址</h4></header>
        <div class="address-list list-block media-list"></div>
        <div class="address-action">
            <div class="flex">
                <div class="flex-1">
                    <a class="btn btn-primary no-radius btn-block address-ok">新增收货地址</a>
                </div>
            </div>
        </div>
        <div class="panel-close">
            <i class="icon icon-cha action-close"></i>
        </div>
    </div>
</section>
<script type="text/html" id="addressItem">
    <ul>
    {%each address as addr%}
        <li class="swipeout" data-id="{%addr.id%}">
            <div class="swipeout-content">
                <div class="item-content">
                    <label class="label-checkbox">
                        <input type="radio" name="address_id" value="{%addr.id%}" autocomplete="off" {%if (addr.is_default)%}checked{%/if%}>
                        <div class="item-media">
                            <i class="icon icon-form-checkbox"></i>
                        </div>
                    </label>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">收件人：<span class="consignee">{%addr.consignee%}</span></div>
                            <div class="item-after"><span class="mobile">{%addr.mobile%}</span></div>
                        </div>
                        <div class="item-text"><span class="address">{%addr.area_info%}</span></div>
                    </div>
                </div>
            </div>
            <div class="swipeout-actions-right">
                <a class="address-edit" href="#">编辑</a>
                <a class="bg-danger swipeout-delete address-del"  data-confirm="确认要删除吗？" href="javascript:;">删除</a>
            </div>
        </li>
    {%/each%}
    </ul>
</script>
<script>
    $(function () {
        var url = {
            save: '/weixin/address/save',
            getList: '/weixin/address/get',
            del: '/weixin/address/del'
        };
        $("#city-picker").cityPicker({
            toolbarTemplate: '<header class="bar bar-nav"><button class="button button-link pull-right close-picker">确定</button></header>',
            cssClass: 'address-picker city-picker'
        });
        var address = {
            address_add: $('.order-address_add'),
            address_area: $('.address-area'),
            init: function () {
                this.shade = this.address_add.find('.all-shade');
                this.panel = this.address_add.find('.address-panel');
                this.form = this.address_add.find('form').get(0);
                return this;
            },
            show: function () {
                var self = this;
                self.shade.addClass('fade_toggle').one('click',function () {
                    self.close();
                });
                self.panel.addClass('active').find('.action-close').one('click',function () {
                    self.close();
                });
                self.panel.find('.address-ok').bind('click',function () {
                    var params = {};
                    params.consignee = $.trim(self.form.consignee.value);
                    if(params.consignee == ''){
                        self.form.consignee.focus();
                        return $.toast('收货人不能为空');
                    }
                    params.mobile = $.trim(self.form.mobile.value);
                    var pattern = $.regex('mobi');
                    if(pattern.test(params.mobile) === false){
                        self.form.mobile.focus();
                        return $.toast('手机号格式不正确');
                    }
                    params.area = self.form.area.value;
                    if(params.area == ''){
                        self.form.area.focus();
                        return $.toast('请选择省、市、区/县');
                    }
                    params.address = $.trim(self.form.address.value);
                    if(params.address == ''){
                        self.form.address.focus();
                        return $.toast('详细地址不能为空');
                    }
                    params.is_defalut = self.form.default.checked?1:0;
                    $.showIndicator();
                    $.post(url.save,params,function (result) {
                        $.hideIndicator();
                        if(result.code == 1){
                            var address = {
                                address_id: result.address_id,
                                consignee: params.consignee,
                                mobile: params.mobile,
                                address: params.area + ' ' + params.address
                            }
                            self.setAddr(address).close();
                        }else{
                            $.alert(result.msg);
                        }
                    });
                });
            },
            setAddr: function (address) {
                this.address_area.find('.consignee').text(address.consignee);
                this.address_area.find('.mobile').text(address.mobile);
                this.address_area.find('.address').text(address.address);
                this.address_area.find('input[name="address_id"]').val(address.address_id);
                console.log(address);
                console.log(this);
                return this;
            },
            close: function () {
                this.shade.removeClass('fade_toggle');
                this.panel.removeClass('active');
                this.panel.find('.address-ok').unbind('click');
            },
            edit: function () {
                return this;
            }
        }.init();

        $('.order-address .no-address').bind('click',function () {
            address.show();
        });

        var address_list = {
            address: address,
            address_select: $('.order-address_select'),
            item: null,
            init: function () {
                var self = this;
                this.shade = this.address_select.find('.all-shade');
                this.panel = this.address_select.find('.address-panel');
                return this;
            },
            show: function () {
                var self = this;
                $.showIndicator();
                $.post(url.getList,function (address) {
                    var html = template('addressItem',{address:address});
                    self.address_select.find('.address-list').html(html).find('.swipeout').on('delete', function (e) {
                        self.del(this,e);
                    });
                    self.address_select.find('.address-edit').bind('click',function (e) {
                        self.edit(this,e);
                    });
                    self.address_select.find('.item-content').bind('click',function (e) {
                        var li = $(this).closest('li');
                        var data = {
                            address_id: li.data('id'),
                            consignee: li.find('.consignee').text().trim(),
                            mobile: li.find('.mobile').text().trim(),
                            address: li.find('.address').text().trim()
                        }
                        self.address.setAddr(data);
                        self.close();
                    })
                    $.hideIndicator();
                    self.shade.addClass('fade_toggle').one('click',function () {
                        self.close();
                    });
                    self.panel.addClass('active').find('.action-close').one('click',function () {
                        self.close();
                    });
                });

            },
            close: function () {
                this.shade.removeClass('fade_toggle');
                this.panel.removeClass('active');
                this.panel.find('.address-ok').unbind('click');
            },
            edit: function (target,e) {
                this.close();
                address.edit().show();
            },
            del: function (target,e) {
                var address_id = $(target).data('id');
                if(address_id > 0)
                    $.ajax({'type':'post','url':url.del,'data':{address_id:address_id}});
            }
        }.init();

        $('.address-area').bind('click',function () {
            var self = this;
            address_list.show();
        });
    });
</script>