<?php
namespace app\weixin\controller;

use app\admin\Model\Banner;
use app\weixin\Model\Goods;
use think\Controller;

class Index extends Controller
{
    public function index(){
        /*D('Common/Wechat','Service');*/

        $bannerModel = new Banner();
        $banners = $bannerModel->getBannersByPositionId(1);
        $this->assign('banners',$banners);

        $goodsModel = new Goods();
        $goods = $goodsModel->getGoods();
fb($goods);
        $this->assign('goods',$goods);

        $this->assign('nav_type',1);
        return $this->fetch();
    }
}
