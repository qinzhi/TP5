<?php
namespace app\admin\controller;

use think\Request;
use think\Controller;
class Index extends Controller
{
    public $app_type = 'public';

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
                    if ($this->psd_verify($password, $admin['password']) === TRUE) {
                        session('_id', $admin['id']);
                        $remember = I('post.remember');
                        if (!empty($remember)) {
                            $saveTime = 7 * 24 * 3600;
                            cookie('_auth',
                                authcode("{$admin['account']}\t{$admin['password']}\t{$admin['id']}", 'ENCODE'), $saveTime);
                        }
                        $result = array('code' => 1, 'msg' => '验证成功');
                    } else {
                        $result = array('code' => 0, 'msg' => '密码不正确');
                    }
                } else {
                    $result = array('code' => 0, 'msg' => '用户名不存在');
                }
            } else {
                $result = array('code' => 0, 'msg' => '验证码不正确');
            }
            return json($result);
        } else {

            $admin_id = session('_id');
            if (!empty($admin_id)) {
                $this->redirect('/'); //跳转首页
            } else{
                return $this->fetch();
            }
        }
    }

    /**
     * 密码验证
     */
    private function psd_verify($inputPsd, $password)
    {
        $inputPsd = md5(md5($inputPsd) . C('DATA_AUTH_KEY'));
        if ($inputPsd == $password) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 注销
     */
    public function logout()
    {
        session(null);
        cookie('_auth', null);
        $this->redirect(U('/login')); //重新登录
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
