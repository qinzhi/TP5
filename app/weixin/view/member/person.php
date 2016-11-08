{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/member.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-order_create" id="page-member_person">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" href="{:url('member/index')}">
            <span class="icon icon-left"></span>
        </a>
        <h1 class="title">个人资料</h1>
    </header>
    <div class="content">
        <div class="list-block">
            <ul>
                <li class="item-content item-avator item-link">
                    <div class="item-inner">
                        <div class="item-title">头像</div>
                        <div class="item-after"><img class="avator" src="{$member.avator|wx_headimgurl=###,96}"/></div>
                    </div>
                </li>
                <li class="item-content item-link">
                    <div class="item-inner">
                        <div class="item-title">昵称</div>
                        <div class="item-after">{$member.nickname}</div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="list-block">
            <ul>
                <li>
                    <a class="item-content item-link" href="{:url('address/index')}">
                        <div class="item-inner">
                            <div class="item-title">收货地址</div>
                            <div class="item-after"><span class="badge badge-danger">2</span></div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
{/block}