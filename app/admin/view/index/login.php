{extend name="layout/login" /}
{block name="content"}
    <div class="page-container">
        <div class="main_box">
            <div class="login_box">
                <div class="login_logo">
                    <h1>登陆</h1>
                </div>

                <div class="login_form">
                    <form id="login_form" method="post" onsubmit="return false;"autocomplete="off">
                        <div class="form-group">
                            <label for="account" class="t">账　号：</label>
                            <input id="account" value="" name="account" type="text" class="form-control x319 in" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="password" class="t">密　码：</label>
                            <input id="password" value="" name="password" type="password"
                                   class="password form-control x319 in" maxlength="20">
                        </div>
                        <div class="form-group">
                            <label for="captcha" class="t">验证码：</label>
                            <input id="captcha" name="captcha" maxlength="4" type="text" class="form-control x212 in">
                            <img id="verify" alt="点击更换" title="点击更换" src="{:url('index/captcha')}" data-src="{:url('index/captcha')}" class="m">
                        </div>
                        <div class="form-group">
                            <label class="t"></label>
                            <label for="remember" class="m">
                                <input id="remember" type="checkbox" name="remember" value="true">&nbsp;一周内免登陆!</label>
                        </div>
                        <div class="form-group space">
                            <label class="t"></label>　　　
                            <button type="button"  id="submit_btn"
                                    class="btn btn-primary btn-lg">&nbsp;登&nbsp;录&nbsp; </button>
                            <input type="reset" value="&nbsp;重&nbsp;置&nbsp;" class="btn btn-default btn-lg">
                        </div>
                    </form>
                </div>
            </div>
            <div class="bottom">Copyright &copy; 2015 - 2016 <a href="#">系统登陆</a></div>
        </div>
    </div>
{/block}
{block name="js"}
    <script>
        var slide = function(step){
            var a=[];
            for(var i=0;i<step;i++){
                a[i] = {};
                a[i].image = '__IMG__/login/slide/'+i+'.jpg';
            }
            return a;
        }(3);
        loading = '__IMG__/login/loading.gif';
        error_icon = '__IMG__/login/error.png';
        $(function(){
            $('#verify').click(function(){
                var src = $(this).data('src');
                $(this).attr('src',src + '?rand=' + Math.random());
            });
        });
    </script>
{/block}