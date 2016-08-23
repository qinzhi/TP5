<?php

namespace app\admin\Model;

use traits\model\SoftDelete;

class Model extends Common{

    use SoftDelete;
    protected static $deleteTime = 'delete_time';


}