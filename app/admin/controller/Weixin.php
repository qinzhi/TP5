<?php

namespace app\admin\controller;

use app\common\service\Wechat;
use app\common\tools\Emotions;
use think\Cache;
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
                Cache::set('wx_menu',Wechat::json_encode($data),0);
                return ['code' => 1,'msg' => '发布成功'];
            }else{
                return ['code' => 0,'msg' => '发布失败'];
            }

        }else{

            $menu = $this->wechatService->getMenu();
            if($menu == false){
                if(Cache::has('wx_menu'))
                    $menu['menu'] = json_decode(Cache::get('wx_menu'),true);
            }

            $this->assign($menu);
            $this->assign('emotions',Emotions::get_qq());

            $this->assign('emotions',Emotions::get_qq());
            return $this->fetch();
        }
    }


}