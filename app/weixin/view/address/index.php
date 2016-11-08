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
    <div class="content infinite-scroll">
    </div>
</div>
{/block}
{block name="js"}
<script type="application/javascript">
</script>
{/block}