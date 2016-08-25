<?php

namespace app\admin\Model;

use traits\model\SoftDelete;

class BannerPosition extends Common{

    use SoftDelete;
    protected static $deleteTime = 'del_time';

    protected $insert = ['create_time'];

    /**
     * 表名
     */
    const TABLE_NAME = 'banner_position';
}