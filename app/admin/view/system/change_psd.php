{extend name="layout/base" /}
{block name="content"}
<div class="row">
    <div class="col-lg-6 col-sm-6 col-xs-12">
        <div class="widget margin-left-50 margin-top-30">
            <div class="widget-header bordered-bottom bordered-blue">
                <span class="widget-caption">修改密码</span>
            </div>
            <div class="widget-body">
                <div>
                    <form role="form" id="modify-form" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">原密码</label>
                            <input class="form-control" id="old_psd" name="old_psd" maxlength="32" type="password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">新密码</label>
                            <input class="form-control" id="new_psd" name="new_psd" maxlength="32" type="password">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">确认新密码</label>
                            <input class="form-control" id="renew_psd" name="renew_psd" maxlength="32" type="password">
                        </div>
                        <button type="submit" class="btn btn-success">确定修改</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="js"}
<script>
    $(function () {
        $('#modify-form').submit(function () {
            var old_psd = this.old_psd.value = $.trim(this.old_psd.value);
            if(old_psd.length <= 0){
                Notify('原密码不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                return false;
            }
            var new_psd = this.new_psd.value = $.trim(this.new_psd.value);
            if(new_psd.length <= 0){
                Notify('新密码不能为空', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                return false;
            }
            var renew_psd = this.renew_psd.value = $.trim(this.renew_psd.value);
            if(new_psd != renew_psd){
                Notify('两次新密码不一致', 'bottom-right', '5000', 'warning', 'fa-warning', true);
                return false;
            }
        });
    });
</script>
{/block}