{extend name="layout/base" /}
{block name="quote-css"}
<link href="__CSS__/goods.css" rel="stylesheet" type="text/css">
{/block}
{block name="page"}
<div class="page page-goods" id="page-goods_list">
    <header class="bar bar-nav">
        <div class="searchbar">
            <div class="search-input">
                <label class="icon icon-fangdajing" for="filtrate"></label>
                <input type="search" id='filtrate' placeholder='搜索商品'/>
            </div>
        </div>
    </header>
    <div class="content">

    </div>
</div>
{/block}