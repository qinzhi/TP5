<?php

namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;


class Goods extends Model
{

    use SoftDelete;

    const TABLE_NAME = 'goods';
    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'goods_delete_time';

    protected $insert = ['create_time', 'update_time'];

    protected $update = ['update_time'];

    public function getGoodsList($params = [],$offset = 0,$limit = 10){
        if(isset($params['status'])){
            $this->where('status',$params['status']);
        }
        if(!empty($params['keyword'])){
            $this->where('name','like','%'.$params['keyword'].'%');
        }
        if(!empty($params['field'])){
            $this->order($params['field'],$params['sort']);
        }
        return $this->limit($offset,$limit)->select();
    }

    public function getGoodsNum($params = []){
        if(isset($params['status'])){
            $this->where('status',$params['status']);
        }
        if(!empty($params['keyword'])){
            $this->where('name','like','%'.$params['keyword'].'%');
        }
        return $this->count();
    }

    /**
     * 获取单个商品
     * @param $id
     * @return mixed
     */
    public function getGoodsById($id){
        return $this->alias('t')
                        ->join(GoodsToDetail::TABLE_NAME . ' t1','t1.goods_id=t.id','LEFT')
                            ->join(GoodsToSeo::TABLE_NAME . ' t2','t2.goods_id=t.id','LEFT')
                                ->where('t.id',$id)->find();
    }

}