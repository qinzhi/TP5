<?php
/**
 * 会员表
 */
namespace app\common\model;

use think\Model;

class Member extends Model{

    /**
     * 表名
     */
    const TABLE_NAME = 'member';

    public $member_id;

    public function __construct()
    {
        parent::__construct();
    }

}