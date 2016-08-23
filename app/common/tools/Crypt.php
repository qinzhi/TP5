<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/21
 * Time: 17:19
 */
namespace app\common\tools;
use think\Config;

/**
 * @class ICrypt
 * @brief ICrypt 加密解密类
 * @note
 */
class Crypt
{
    /**
     * @brief  MD5加密方法
     * @param  string $string  字符串
     * @return string $string  加密后的字符串
     */
    public static function md5($string)
    {
        return md5($string);
    }

    /**
     * @brief base64加密方法
     * @param  String $str 字符串
     * @return String $str 加密后字符串
     */
    public static function base64encode($str)
    {
        return base64_encode($str);
    }

    /**
     * @brief  base64解密方法
     * @param  String $str  字符串
     * @return String $str  解密后的字符串
     */
    public static function base64decode($str)
    {
        return base64_decode($str);
    }

    /**
     * @brief 动态解密函数
     * @param  String $string 字符串
     * @param  String $key    解密私钥
     * @param  Int    $expiry 保留时间，默认为0，即为不限制
     * @return String $str    解密后的字符串
     */
    public static function decode($string, $key='', $expiry=0)
    {
        return self::code($string,'decode', $key, $expiry);
    }

    /**
     * @brief 动态解密函数
     * @param  String  $string 字符串
     * @param  String  $key    加密私钥
     * @param  Int     $expiry 保留时间，默认为0，即为不限制
     * @return $string String  加密后的字符串
     */
    public static function encode($string, $key='', $expiry=0)
    {
        return self::code($string,'encode', $key, $expiry);
    }
    /**
     * @brief 加密解密算法
     * @param  String  $string  要处理的字符串
     * @param  String  $op      处理方式，加密或者解密，默认为decode即为解密
     * @param  Int     $expiry  保留时间，默认为0即为不限制
     * @return String  $string  处理后的字符串
     */
    private static function code($string, $op="decode", $key='', $expiry=0)
    {
        $op=strtolower($op);
        $key_length=10;
        $key=md5($key?$key:C('DATA_AUTH_KEY'));
        //生成256长度的密码
        $key_1=md5(substr($key,0,4));
        $key_2=md5(substr($key,4,4));
        $key_3=md5(substr($key,8,4));
        $key_4=md5(substr($key,12,4));
        $key_5=md5(substr($key,16,4));
        $key_6=md5(substr($key,20,4));
        $key_7=md5(substr($key,24,4));
        $key_8=md5(substr($key,28,4));
        $key_e= $key_length ? ($op == 'decode' ? substr($string, 0, $key_length): substr(md5(microtime()), -$key_length)) : '';
        $cryptkey = md5($key_1|$key_e).md5($key_3|$key_e).md5($key_5|$key_e).md5($key_7|$key_e).md5($key_8|$key_e).md5($key_6|$key_e).md5($key_4|$key_e).md5($key_2|$key_e);
        $cryptkey_length = strlen($cryptkey);
        $string = $op == 'decode' ? self::base64decode(substr($string, $key_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$key_5), 0, 22).$string;
        $string_length = strlen($string);
        $result="";
        //通过循环的方式异或的方式加密，异或方式是加密中常用的一种处理方式
        for($i = 0; $i < $string_length; $i++)
        {
            $result .= chr(ord($string[$i]) ^ ($cryptkey[$i % 256]));
        }
        //解码部分
        if($op == 'decode')
        {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 22) == substr(md5(substr($result, 32).$key_5), 0, 22))
            {
                return substr($result, 32);
            } else
            {
                return '';
            }
        }
        else
        {
            return $key_e.str_replace('=', '', self::base64encode($result));
        }
    }

    /**
     *用户信息加密解密函数
     *
     *待加密内容用\t分割
     *@return String 加密或解密字符串
     *@param String $string 待加密或解密字符串
     *@param String $operation 操作类型定义 DECODE=解密 ENDODE=加密
     *@param String $key 加密算子
     */
    public static function authcode($string, $operation, $key = '') {
        /**
         *获取密码算子,如未指定，采取系统默认算子
         *默认算子是论坛授权码和用户浏览器信息的md5散列值
         *$GLOBALS['discuz_auth_key']----全局变量
         *取值为:md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT'])
         *$_DCACHE['settings']['authkey']是论坛安装时生成的15位随机字符串
         */
        $key = md5($key ? $key : Config::get('auth_key'));
        $key_length = strlen($key);
        /**
         *如果解密，先对密文解码
         *如果加密,将密码算子和待加密字符串进行md5运算后取前8位
         *并将这8位字符串和待加密字符串连接成新的待加密字符串
         */
        $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        /**
         *初始化加密变量,$rndkey和$box
         */
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        /**
         *$box数组打散供加密用
         */
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        /**
         *$box继续打散,并用异或运算实现加密或解密
         */
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
}