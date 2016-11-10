<?php
namespace app\admin\controller;

use app\admin\model\AuthRole;
use app\admin\model\Admin as AdminModel;
use app\common\tools\Tree;
use think\Request;
use think\Controller;
use think\Session;

class Admin extends Controller
{
    public $admin;

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

        $admin_id = Session::get('id');

        $this->admin = AdminModel::where('id',$admin_id)->find();

        if(empty($this->admin)){
            die('管理员不存在');
        }
    }
}