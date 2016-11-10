<?php

namespace app\admin\behavior;
use app\admin\model\Admin;
use app\common\tools\Crypt;
use think\Request;
use think\response\Redirect;

class AuthCheck{
    protected $config;
    public function run(&$params) {
        list($param,$method) = $params;

        $param->app_type = isset($param->app_type)?$param->app_type:'';

        switch ($param->app_type) {
            case 'public': {
                return;
            }
            default:
                $this->ckeckLogin();
        }

    }

    //检查是否登陆
    private function ckeckLogin(){
        $admin_id = session('id');
        $auth = cookie('auth');
        if(empty($admin_id) && !empty($auth)){
            list($account,$password) = explode("\t", Crypt::authcode($auth, 'DECODE'));
            $admin = Admin::where('account',$account)->where('password',$password)->find();
            if(!empty($admin)){
                return session('id',$admin['id']);
            }
        }elseif(empty($admin_id) && empty($auth)){
            (new Redirect('index/login'))->send();
            exit;
        }
    }
}
?>