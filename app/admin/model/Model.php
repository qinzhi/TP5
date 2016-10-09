<?php

namespace app\admin\model;

use traits\model\SoftDelete;

class Model extends Common{

    use SoftDelete;
    protected static $deleteTime = 'delete_time';

    const TABLE_NAME = 'model';
}