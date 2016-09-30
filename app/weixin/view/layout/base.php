<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="description" content="{$seo['description']?:'水果'}">
    <meta name="keywords" content="{$seo['keywords']?:'水果'}">

    {block name="title"}
        <title>{$title?:'果度商城'}</title>
    {/block}
    <link rel="stylesheet" href="__LIGHT7__/css/light7.min.css">
    <link rel="stylesheet" href="__LIGHT7__/css/light7-swiper.min.css">
    <link href="__COMMON__/css/guodu.css" rel="stylesheet" type="text/css">
    <link href="__CSS__/style.css" rel="stylesheet" type="text/css">
    {block name="quote-css"}{/block}
    {block name="css"}{/block}
    <script type='text/javascript' src='__STATIC__/js/jquery-2.1.4.min.js' charset='utf-8'></script>
    <!--<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>-->
    {include file="Layout:config"/}
</head>
<body>
    {block name="page"}{/block}
    <script type='text/javascript' src='__LIGHT7__/js/light7.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='__LIGHT7__/js/i18n/cn.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='__LIGHT7__/js/light7-swiper.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='__JS__/lib.js' charset='utf-8'></script>
    <script src="__STATIC__/js/template.js"></script>
    {block name="quote-js"}{/block}
    {block name="js"}{/block}
</body>
</html>