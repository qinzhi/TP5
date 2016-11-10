<?php
/**
 * 获取图片绝对地址
 * @param $src
 * @return string
 */
function get_img_url($src){
    return config('resource_url') . $src;
}

/**
 * 获取图片绝对路径
 * @param $src
 * @return string
 */
function get_img_path($src){
    return config('resource_path') . $src;
}

function wx_headimgurl($img_src,$type){
    $img_src = substr($img_src,0,strlen($img_src) - 1);
    return $img_src . $type;
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

// 获取IP地址（摘自discuz）
function get_client_ip(){
    $ip='未知IP';
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        return is_ip($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:$ip;
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        return is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;
    }else{
        return is_ip($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;
    }
}

function is_ip($str){
    $ip=explode('.',$str);
    for($i=0;$i<count($ip);$i++){
        if($ip[$i]>255){
            return false;
        }
    }
    return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$str);
}