<?php

namespace app\weixin\model;

use think\Model;

class Common extends Model{

    public $member_id;//用户id

    public function initialize()
    {
        parent::initialize();
        //parent::__construct();

        $this->member_id = 1;
    }
}