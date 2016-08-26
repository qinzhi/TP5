<?php

namespace app\admin\Model;

use traits\model\SoftDelete;

class Products extends Common{

    use SoftDelete;
    protected static $deleteTime = 'del_time';

    /**
     * 表名
     */
    const TABLE_PRODUCT = 'products';
}