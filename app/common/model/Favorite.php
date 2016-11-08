<?php

namespace app\common\model;

use think\Model;

class Favorite extends Model
{

    const TABLE_NAME = 'favorite';

    public function getGoodsList($member_id,$offset = 0,$limit = 10){
        return $this->alias('t1')
                        ->join(Goods::TABLE_NAME . ' as t2','t1.goods_id=t2.id','left')
                            ->where('member_id',$member_id)->order('t1.id','desc')
                                ->limit($offset,$limit)->select();
    }

    public function getGoodsNum($member_id){
        return $this->where('member_id',$member_id)->count();
    }

}