<nav class="bar bar-tab">
    <a class="tab-item {$nav_type == 1?'active':''}" href="/">
        <span class="icon icon-zhuye"></span>
        <span class="tab-label">首页</span>
    </a>
    <a class="tab-item {$nav_type == 2?'active':''}" href="{:U('/category')}">
        <span class="icon icon-suoyou"></span>
        <span class="tab-label">分类</span>
    </a>
    <a class="tab-item {$nav_type == 3?'active':''}" href="{:U('/cart')}" data-no-cache="true">
        <span class="icon icon-gouwuche"></span>
        <span class="tab-label">货单</span>
        <span class="badge" id="cart_num">{$cartNum|default=0}</span>
    </a>
    <a class="tab-item {$nav_type == 4?'active':''}" href="{:U('/me')}">
        <span class="icon icon-quanyonghu"></span>
        <span class="tab-label">我的</span>
    </a>
</nav>