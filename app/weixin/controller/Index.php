<?php
namespace app\weixin\controller;

use think\Controller;

class Index extends Controller
{
    public function index(){
        /*D('Common/Wechat','Service');

        $banners = D('Admin/Banner')->getBannersByPositionId(1);
        $this->assign('banners',$banners);

        $goods = D('Goods')->getGoods();

        $this->assign('goods',$goods);

        $this->assign('nav_type',1);*/
        return $this->fetch();
    }
}
