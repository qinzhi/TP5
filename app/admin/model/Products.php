<?php

namespace app\admin\Model;

use traits\model\SoftDelete;

class Products extends Common{
    use SoftDelete;
    protected static $deleteTime = 'delete_time';
}