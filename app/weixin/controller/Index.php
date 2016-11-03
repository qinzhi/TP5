<?php
namespace app\weixin\controller;

use app\admin\model\Banner;
use app\common\model\Goods;

class Index extends Weixin
{
    public function index(){
        /*D('Common/Wechat','Service');*/
        $bannerModel = new Banner();
        $banners = $bannerModel->getBannersByPositionId(1);
        $this->assign('banners',$banners);

        $goodsModel = new Goods();

        $goods = $goodsModel->getGoodsList();

        $this->assign('goods',$goods);

        $this->assign('cartNum',$this->getCartNum());
        $this->assign('nav_type',1);
        return $this->fetch();
    }
}
