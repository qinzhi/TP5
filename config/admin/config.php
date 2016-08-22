<?php

\think\Config::set('session.prefix','admin_');//session前缀
\think\Config::set('cookie.prefix','admin_');//cookie前缀

//配置文件
return [

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------
    
    // URL伪静态后缀
    'url_html_suffix' => '',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    // 视图输出字符串内容替换
    'view_replace_str' => array_merge(config('view_replace_str'),[
        '__CKEDITOR__' => '/Ckeditor', // 富文本编辑器
        '__CKFINDER__' => '/Ckfinder', // 图片资源管理器
        '__ADMIN__' => '/admin',
        '__ADDONS__' => '/admin/Addons',
        '__IMG__'    => '/admin/img',
        '__CSS__'    => '/admin/css',
        '__JS__'     => '/admin/js',
        '__OWN__'     => '/admin/own',
        '__OWNJS__'     => '/admin/own/js',
    ]),

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'captcha'  => [
        // 验证码字符集合
        'codeSet'  => '12345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字体大小(px)
        'fontSize' => 14,
        // 是否画混淆曲线 默认为true
        'useCurve' => false,
        //是否添加杂点 默认为true
        'useNoise' => false,
        // 验证码图片高度
        'imageH'   => 32,
        // 验证码图片宽度
        'imageW'   => 100,
        // 验证码位数
        'length'   => 5,
        // 验证成功后是否重置
        'reset'    => true,
        //验证码背景颜色 rgb数组设置，例如 array(255, 255, 255)
        'bg' => array(255, 255, 255), 
    ],
];