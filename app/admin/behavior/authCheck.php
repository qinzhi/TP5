<?php

namespace app\admin\behavior;
use app\common\tools\Crypt;
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
        $admin_id = session('admin_id');
        $_id = cookie('_id');
        if(empty($admin_id) && !empty($_auth)){
            $_id = Crypt::authcode($_id, 'DECODE');
            session('admin_id',$_id);
        }elseif(empty($admin_id) && empty($_auth)){
            (new Redirect('index/login'))->send();
            exit;
        }
    }
}
?>