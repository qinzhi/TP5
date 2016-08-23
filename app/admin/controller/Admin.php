<?php
namespace app\admin\controller;

use app\admin\Model\AuthRole;
use app\common\tools\Tree;
use think\Request;
use think\Controller;

class Admin extends Controller
{
    public function __construct(){

        parent::__construct();

        if(Request::instance()->isAjax() === false){
            $authRole = new AuthRole();
            $menu = $authRole->getMenu();
            $slideBar = (new Tree($menu))->leaf();

            $authRole->getBreadcrumbs();
            $breadcrumbs = $authRole->breadcrumbs;

            $this->assign('breadcrumbs',array_reverse($breadcrumbs));
            $this->assign('slideBar',$slideBar);
        }

    }
}