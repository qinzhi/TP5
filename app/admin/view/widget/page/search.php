<!-- Page Sidebar Header-->
<div class="sidebar-header-wrapper">
    <input type="text" class="searchinput" id="global_input" autocomplete="off"/>
    <i class="searchicon fa fa-search"></i>
    <div class="searchhelper">
        <ul class="global_search_ul">
            <li><a href="javascript:;">订单搜索: <span class="li-keyword text-success"></span></a></li>
            <li><a href="javascript:;">会员搜索: <span class="li-keyword text-success"></span></a></li>
            <li><a href="javascript:;">商品搜索: <span class="li-keyword text-success"></span></a></li>
        </ul>
    </div>
</div>
<script>
    $(function(){
        $('#global_input').bind('keyup',function(){
            var val = $.trim($(this).val());
            $(this).parent().find('.global_search_ul li').each(function(){
                $(this).find('.li-keyword').text(val);
            });
        });
    });
</script>
<!-- /Page Sidebar Header -->