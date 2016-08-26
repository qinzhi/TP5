<?php

namespace app\weixin\Model;

use think\Model;

class Common extends Model{

    public $user_id;//用户id

    public function __construct()
    {
        parent::__construct();

        $this->user_id = 1;
    }
}