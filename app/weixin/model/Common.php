<?php

namespace app\weixin\model;

use think\Model;

class Common extends Model{

    public $user_id;//用户id

    public function initialize()
    {
        parent::initialize();
        //parent::__construct();

        $this->user_id = 1;
    }
}