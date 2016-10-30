<nav class="bar bar-tab">
    <a class="tab-item {$nav_type == 1?'active':''}" href="index">
        <span class="icon icon-zhuye"></span>
        <span class="tab-label">首页</span>
    </a>
    <a class="tab-item {$nav_type == 2?'active':''}" href="{:url('category/index')}">
        <span class="icon icon-suoyou"></span>
        <span class="tab-label">分类</span>
    </a>
    <a class="tab-item {$nav_type == 3?'active':''}" href="{:url('cart/index')}" data-no-cache="true">
        <span class="icon icon-gouwuche"></span>
        <span class="tab-label">购物车</span>
        <span class="badge" id="cart_num">{$cartNum|default=0}</span>
    </a>
    <a class="tab-item {$nav_type == 4?'active':''}" href="{:url('member/index')}">
        <span class="icon icon-quanyonghu"></span>
        <span class="tab-label">我的</span>
    </a>
</nav>