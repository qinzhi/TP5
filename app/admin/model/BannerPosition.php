<?php

namespace app\admin\Model;

use traits\model\SoftDelete;

class BannerPosition extends Common{

    use SoftDelete;
    protected static $deleteTime = 'delete_time';

    protected $insert = ['create_time'];
}