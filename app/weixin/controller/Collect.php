<?php
/**
 * 我的收藏
 */
namespace app\weixin\controller;

use app\common\model\Favorite;
use think\Request;

class Collect extends Weixin
{

    public $limit = 10;

    public function index(){
        $this->assign('cartNum',$this->getCartNum());
        $this->assign('limit',$this->limit);
        return $this->fetch();
    }

    public function getGoodsList(){
        $page = Request::instance()->request('page',1);
        $offset = ($page - 1) * $this->limit;
        $favoriteModel = new Favorite();
        $goodsList = $favoriteModel->getGoodsList($this->member['id'],$offset,$this->limit);
        foreach ($goodsList as $key => &$val){
            $val['cover_image'] = get_img_url($val['cover_image']);
            $val['url'] = url('goods/detail',['id'=>$val['id']]);
        }
        $result['goodsNum'] = $favoriteModel->getGoodsNum($this->member['id']);
        $result['goodsList'] = $goodsList;
        return $result;
    }
}
