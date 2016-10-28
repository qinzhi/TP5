<?php

namespace app\weixin\controller;



class Member extends Weixin  {

    public function index(){
        $this->assign('nav_type',4);
        return $this->fetch();
    }
}