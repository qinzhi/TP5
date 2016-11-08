<?php
/**
 * 充值
 */
namespace app\weixin\controller;

class Integral extends Weixin
{
    public function index(){
        return $this->fetch();
    }
}
