<?php
namespace app\weixin\controller;

class Category extends Weixin
{
    public function index(){        
        return $this->fetch();
    }
}
