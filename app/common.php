<?php
/**
 * 获取图片绝对路径
 * @param $src
 * @return string
 */
function get_img($src){
    return config('resource_path') . $src;
}

/**
 * 获取站点URL
 * @return string
 */
function get_site_url()
{
    $scheme	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
    $host	= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_ADDR'];
    $port	= $_SERVER['SERVER_PORT'] == 80 ? '' : ':'.$_SERVER['SERVER_PORT'];

    return $scheme.$host.$port;
}

/**
 * 获取当前页面的完整URL，包括协议、域名、路径和查询字符串。
 * @return string
 */
function get_full_url()
{
    return get_site_url().$_SERVER['REQUEST_URI'];
}
