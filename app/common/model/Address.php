<?php
/**
 * 收货地址表
 */
namespace app\common\model;

use think\Model;

class Address extends Model{

    /**
     * 表名
     */
    const TABLE_NAME = 'address';

    public $member_id;

    public function __construct($member_id)
    {
        parent::__construct();
        $this->member_id = $member_id;
    }

    /**
     * 获取用户收货地址列表
     * @return mixed
     */
    public function getList(){
        return $this->query($this->where('member_id',$this->member_id)->order(['is_default desc','id desc'])->buildSql());
    }

    /**
     * 获取用户默认地址
     * @return array|false|\PDOStatement|string|Model
     */
    public function getDefault(){
        return $this->where('member_id',$this->member_id)->where('is_default',1)->find();
    }

    /**
     * 设置用户默认地址
     * @return mixed
     */
    public function setDefault(){
        $sql = $this->field('max(id) as id')->where('member_id',$this->member_id)->buildSql();
        return $this->alias('t')
                        ->join($sql . ' t1','t1.id=t.id')
                            ->where('member_id',$this->member_id)->setField('is_default',1);
    }

    //清除用户默认地址
    public function clearDefault(){
        return $this->where('member_id',$this->member_id)->update(['is_default' => 0]);;
    }
}