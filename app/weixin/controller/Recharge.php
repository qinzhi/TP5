<?php
/**
 * 充值
 */
namespace app\weixin\controller;

class Recharge extends Weixin
{
    public function index(){
        return $this->fetch();
    }
}
