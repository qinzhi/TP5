<!DOCTYPE html>
<!--
BeyondAdmin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.2.0
Version: 1.0.0
Purchase: http://wrapbootstrap.com
-->

<html xmlns="http://www.w3.org/1999/xhtml">

<!-- Head -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    {block name="title"}<title>网站后台</title>{/block}
    <!--[if lt IE 9]>
    <script src="https://blogs.msdn.microsoft.com/ie/wp-content/themes/microsoft/js/html5.js"></script>
    <![endif]-->
    {block name="meta"}
        <meta name="description" content="blank page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" href="__IMG__/favicon.png" type="image/x-icon">
    {/block}

    {include file="Layout/basic_css" /}

    {block name="plugin_css"}
        <link href="__CSS__/dataTables.bootstrap.css" rel="stylesheet" />
    {/block}

    {block name="own_css"}
        <link href="__CSS__/soa.min.css" rel="stylesheet" />
    {/block}

    {block name="css"}{/block}

    <link id="skin-link" href="__CSS__/skins/green.min.css" rel="stylesheet" type="text/css" />
    <script src="__STATIC__/js/jquery.min.js"></script>
    <script src="__STATIC__/js/template.js"></script>
    <script type="application/javascript">
        (function () {
            template.config('openTag', '{%');
            template.config('closeTag', '%}');
        })();
    </script>
    <!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
    <script src="__JS__/skins.min.js"></script>

    {block name="own_js"}
        <script src="__JS__/lib.js"></script>
    {/block}
</head>
<body>

    {block name="loading"}
        {:widget('Page/loading')}
    {/block}

    {block name="navbar"}
        {:widget('Page/navBar')}
    {/block}

    <div class="main-container container-fluid">
        <!-- Page Container -->
        <div class="page-container">

            <div class="page-sidebar" id="sidebar">
                {block name="search"}
                    {:widget('Page/search')}
                {/block}

                {block name="slideBar"}
                    {:widget('Page/sideBar',[$slideBar])}
                {/block}
            </div>

            <!-- Page Content -->
            <div class="page-content">

                {block name="breadcrumbs"}
                    {:widget('Page/breadcrumbs',[$breadcrumbs])}
                {/block}

                <!--<div class="page-header position-relative">
                    <block name="header-title">
                        {:widget('Page/title',array($breadcrumbs))}
                    {/block}
                    <div class="header-buttons">
                        <a class="sidebar-toggler" href="#">
                            <i class="fa fa-arrows-h"></i>
                        </a>
                        <a class="refresh" id="refresh-toggler" href="">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </a>
                        <a class="fullscreen" id="fullscreen-toggler" href="#">
                            <i class="glyphicon glyphicon-fullscreen"></i>
                        </a>
                    </div>
                </div>-->

                <!-- Page Body -->
                <div class="page-body no-padding">
                    {block name="content"}{/block}
                </div>
                <!-- /Page Body -->
            </div>
        </div>
    </div>

    {block name="plugin_js"}
        {include file="Layout/plugin_js" /}
    {/block}

    {block name="js"}{/block}

</body>
</html>
