<?php

namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use think\Request;

class System extends Admin {

    /**
     * 修改密码
     * @return mixed
     */
    public function change_psd(){
        if(Request::instance()->isPost()){
            $old_psd  = Request::instance()->post('old_psd');
            if(empty($old_psd)){
                $this->error('原密码不能为空！');
            }
            $new_psd  = Request::instance()->post('new_psd');
            if(empty($new_psd)){
                $this->error('新密码不能为空！');
            }
            $renew_psd  = Request::instance()->post('renew_psd');
            if($new_psd != $renew_psd){
                $this->error('两次新密码不一致！');
            }
            if(password_encrypt($old_psd) == $this->admin['password']){
                AdminModel::where('id',$this->admin['id'])->update([
                    'password' => password_encrypt($new_psd)
                ]);
                $this->redirect('index/logout');
            }else{
                $this->error('原密码错误，请重新修改！');
            }
        }else{
            return $this->fetch();
        }
    }

}