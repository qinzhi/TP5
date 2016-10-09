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
}