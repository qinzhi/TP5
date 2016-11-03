<?php
namespace app\weixin\controller;

class Category extends Weixin
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('nav_type',2);
    }

    public function index(){
        $this->assign('cartNum',$this->getCartNum());
        return $this->fetch();
    }
}
