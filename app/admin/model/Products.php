<?php

namespace app\admin\Model;

use traits\model\SoftDelete;

class Products extends Common{

    use SoftDelete;
    protected static $deleteTime = 'products_delete_time';

    /**
     * 表名
     */
    const TABLE_PRODUCT = 'products';
}