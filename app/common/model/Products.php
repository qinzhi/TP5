<?php

namespace app\common\model;

use think\Model;
use traits\model\SoftDelete;

class Products extends Model{

    use SoftDelete;
    protected static $deleteTime = 'products_delete_time';

    /**
     * 表名
     */
    const TABLE_NAME = 'products';
}