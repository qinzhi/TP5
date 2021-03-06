<script>
    $.config = {
        router: false,
        autoInit: true,
        showPageLoadingIndicator: true,
        replace: true,
        // 路由功能开关过滤器，返回 false 表示当前点击链接不使用路由
        routerFilter: function($link) {
            // 某个区域的 a 链接不想使用路由功能
            if ($link.is('.disable-router a')) {
                return false;
            }
            return true;
        },
        getWeixinSign: '{:url("home/getWeixinSign")}',
        cookie_prefix: '{:config("cookie.prefix")}'
    };
    $(function () {
        template.config('openTag', '{%');
        template.config('closeTag', '%}');
    })
</script>