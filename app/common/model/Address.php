<?php
/**
 * 购车表
 */
namespace app\common\model;

use think\Model;

class Address extends Model{

    /**
     * 表名
     */
    const TABLE_NAME = 'address';

    public $user_id;

    public function __construct($user_id)
    {
        parent::__construct();
        $this->user_id = $user_id;
    }

    public function getList(){
        return $this->query($this->where('user_id',$this->user_id)->order(['is_default desc','id desc'])->buildSql());
    }

    public function getDefault(){
        return $this->where('is_default',1)->where('user_id',$this->user_id)->find();
    }

    //清除用户默认地址
    public function clearDefault(){
        return $this->where('user_id',$this->user_id)->update(['is_default' => 0]);;
    }
}