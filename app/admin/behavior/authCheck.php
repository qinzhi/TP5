<?php

namespace app\admin\behavior;
use Common\Library\Org\Util\Crypt;

class AuthCheck{
    protected $config;
    public function run(&$params) {
        list($param,$method) = $params;
        switch ($param->app_type) {
            case 'public': {
                return;
            }
        }

        /*$admin_id = session('_admin_id');
        if(empty($admin_id)){
            $_account = cookie('_account');
            $_password = cookie('_password');
            if(!empty($_account) && !empty($_password)){
                $_account = Crypt::decode($_account);
                $_password = Crypt::decode($_password);
                $admin = M('Admin')->where(array('account'=>$_account))->find();
                if(!empty($admin) && $_password == $admin['password']){
                    session('_admin_id',$admin['id']);
                }
            }else{
                header('Location: /login');
            }
        }*/
    }
}
?>