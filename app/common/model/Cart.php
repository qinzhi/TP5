<?php
/**
 * 购车表
 */
namespace app\common\model;

use think\Model;

class Cart extends Model{

    /**
     * 表名
     */
    const TABLE_NAME = 'cart';

    public function __construct()
    {
        parent::__construct();
    }

    public function getList($member_id,$cart_id = []){
        $cart_field = 'c.id as cart_id,c.product_id,c.cart_num,c.member_id,c.is_selected';
        $product_field = ',p.goods_id,p.products_no,p.spec_array,p.store_nums,p.sell_price,p.market_price,p.cost_price';
        $goods_field = ',g.name,g.cover_image,g.unit,g.sale';
        $this->field($cart_field . $product_field . $goods_field)
                        ->alias('c')
                            ->join(Products::TABLE_NAME . ' p','c.product_id = p.id')
                                ->join(Goods::TABLE_NAME . ' g','p.goods_id = g.id')
                                    ->where('c.member_id',$member_id);
        if(!empty($cart_id)){
            $this->where('c.id','in',$cart_id);
        }
        return $this->query($this->buildSql());
    }

    public function deleteByIds($ids){
        return $this->where('id','in',$ids)->delete();
    }
}