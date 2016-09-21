<?php

namespace app\admin\behavior;
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
        $admin_id = session('admin_id');
        $_id = cookie('id');
        if(empty($admin_id) && !empty($_id)){
            $_id = intval(Crypt::authcode($_id, 'DECODE'));
            if($_id > 0){
                return session('admin_id',$_id);
            }
        }elseif(empty($admin_id) && empty($_auth)){
            (new Redirect('index/login'))->send();
            exit;
        }
    }
}
?>