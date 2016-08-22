<?php

namespace app\admin\controller;

class Home extends Admin {

    public function index(){
        return $this->fetch();
    }

}