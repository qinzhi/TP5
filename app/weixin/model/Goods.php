<?php

namespace app\weixin\Model;

use app\admin\Model\Products;
use app\common\Model\GoodsToAttr;
use app\common\Model\GoodsToCategory;
use app\common\Model\GoodsToCommend;
use app\common\Model\GoodsToDetail;
use app\common\Model\GoodsToSeo;
use think\Db;
use think\Request;
use traits\model\SoftDelete;

class Goods extends Common{

    use SoftDelete;
    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'delete_time';

    public function getGoods(){

        $cartSql = Db::name('cart')->where('user_id',$this->user_id)->buildSql();

        $sql = $this->field('g.*,p.*,c.num as cart_num')
                        ->alias('g')
                        ->join("$cartSql as c",'g.id=c.goods_id','left')
                        ->join(Products::TABLE_PRODUCT . ' as p','g.id=p.goods_id','left')
                        ->where('status',1)->where('p.del_time IS NULL')->buildSql();
        $goods = Db::query($sql);
        $arr = [];
        foreach($goods as $val){
            if(empty($arr[$val['id']])){
                $arr[$val['id']] = $val;
            }
            $arr[$val['id']]['products'][] = [
                'products_no' => $val['products_no'],
                'spec_array' => $val['spec_array'],
                'store_nums' => $val['store_nums'],
                'market_price' => $val['market_price'],
                'sell_price' => $val['sell_price'],
                'cost_price' => $val['cost_price'],
                'weight' => $val['weight'],
                'is_default' => $val['is_default'],
            ];
        }
        return $arr;
    }
}