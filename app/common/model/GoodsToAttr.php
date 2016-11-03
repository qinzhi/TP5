<?php
/**
 * 商品属性表
 */
namespace app\common\model;

use think\Model;

class GoodsToAttr extends Model{

    /**
     * 表名
     */
    const TABLE_NAME = 'goods_to_attr';


    public function getGoodsAttr($goods_id){
        return $this->alias('t')->join(Attr::TABLE_NAME . ' as t1','t.attr_id=t1.id')->where('goods_id',$goods_id)->select();
    }
}