<?php

namespace app\admin\controller;

use think\Config;
use think\Cookie;
use think\Request;
use think\Controller;
use app\common\tools\Crypt;
use think\Session;
use think\Url;

class Index extends Controller
{

    public $app_type = 'public';

    const COOKIE_EXPIRE = 604800;//7天

    public function index(){
        $this->redirect(url('index/login'));
    }

    /**
     * 登录
     */
    public function login()
    {
        if (Request::instance()->isPost()) {
            $captcha = Request::instance()->post('captcha','','trim');
            if (captcha_check($captcha)) {
                $account = Request::instance()->post('account');
                $password = Request::instance()->post('password');
                $adminModel = model('Admin');
                $admin = $adminModel::get(['account' => $account]);
                if (!empty($admin)) {
                    if ($this::psd_verify($password, $admin['password']) === true) {
                        Session::set('admin_id',$admin['id']);
                        if (Request::instance()->has('remember','post')) {
                            Cookie::set('id',Crypt::authcode("{$admin['id']}", 'ENCODE'), self::COOKIE_EXPIRE);
                        }
                        return ['code'=>1,'msg'=>'验证成功','url'=>Url::build('home/index')];
                    } else {
                        return ['code'=>0,'msg'=>'密码不正确'];
                    }
                } else {
                    return ['code'=>0,'msg'=>'用户名不存在'];
                }
            } else {
                return ['code'=>0,'msg'=>'验证码不正确'];
            }
        } else {
            if (Session::has('admin_id')) {
                $this->redirect('home/index'); //跳转首页
            } else{
                return $this->fetch();
            }
        }
    }

    /**
     * 密码验证
     */
    public static function psd_verify($inputPsd, $password)
    {
        $inputPsd = md5(md5($inputPsd) . Config::get('auth_key'));
        if ($inputPsd == $password) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 注销
     */
    public function logout()
    {
        Session::clear(Config::get('session.prefix'));
        Cookie::clear(Config::get('cookie.prefix'));
        $this->redirect('index/login'); //重新登录
    }

    /**
     * 验证码
     */
    public function captcha()
    {
        return captcha('',[
                    // 验证码字符集合
                    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
                    // 验证码字体大小(px)
                    'fontSize' => 15,
                    // 是否画混淆曲线 默认为true
                    'useCurve' => false,
                    //是否添加杂点 默认为true
                    'useNoise' => false,
                    // 验证码图片高度
                    'imageH'   => 32,
                    // 验证码图片宽度
                    'imageW'   => 100,
                    // 验证码位数
                    'length'   => 4,
                    // 验证成功后是否重置
                    'reset'    => true,
                    //验证码背景颜色 rgb数组设置，例如 array(255, 255, 255)
                    'bg' => array(255, 255, 255),
                ]);
    }
}
