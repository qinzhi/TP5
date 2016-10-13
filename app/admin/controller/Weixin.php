<?php

namespace app\admin\controller;

use app\common\service\Wechat;
use app\common\tools\Emotions;
use think\Request;

class Weixin extends Admin {

    public $wechatService;

    public function __construct()
    {
        parent::__construct();
        $this->wechatService = new Wechat();
    }

    public function setting_menu(){
        if(Request::instance()->isPost()){

            $buttons = Request::instance()->request('buttons/a');
            $data['button'] = $buttons;

            if(!empty($buttons) && $this->wechatService->createMenu($data) !== FALSE){
                return ['code' => 1,'msg' => '发布成功'];
            }else{
                return ['code' => 0,'msg' => '发布失败'];
            }

        }else{

            $this->assign($this->wechatService->getMenu());

            $this->assign('emotions',Emotions::get_qq());
            return $this->fetch();
        }
    }


}