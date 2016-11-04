<?php
namespace app\weixin\controller;

class Evaluate extends Weixin
{
    public $limit = 10;

    public function index(){
        $this->assign('limit',$this->limit);
        return $this->fetch();
    }

    public function evaluateList(){
        return ['data' => ['','',''],'pageNum' => 1];
    }
}
