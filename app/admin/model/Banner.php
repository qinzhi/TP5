<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/27
 * Time: 14:40
 */
namespace app\admin\Model;

use traits\model\SoftDelete;

class Banner extends Common{

    use SoftDelete;
    protected static $deleteTime = 'delete_time';

    protected $insert = ['create_time','update_time'];

    protected $update = ['update_time'];

    /**
     * 表名
     */
    const TABLE_NAME = 'banner';

    public function setCreateTimeAttr(){
        return time();
    }

    public function setUpdateTimeAttr(){
        return time();
    }

    public function getList(){
        return $this->alias('b')
                        ->field('b.*,p.name as position_name')
                            ->join(BannerPosition::TABLE_NAME . ' p','b.position_id=p.id','left')
                                ->select();
    }

    public function getBannersByPositionId($position_id){
        return $this->where('position_id',$position_id)->where('status',1)->limit(5)->select();
    }
}