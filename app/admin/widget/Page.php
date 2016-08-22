<?php
namespace app\admin\widget;
use think\Controller;
class Page extends Controller {

    protected $config = array('app_type' => 'public');

    public function headerTitle($name) {
        return $this -> fetch('widget:page/headerTitle',['name'=>$name]);
    }

    public function loading(){
        return $this -> fetch('widget:page/loading');
    }

    public function navBar() {
        return $this -> fetch('widget:page/navBar');
    }

    public function sideBar($slideBar) {
        return $this -> fetch('widget:page/sideBar',['slideBar'=>$slideBar]);
    }

    public function breadcrumbs($breadcrumbs) {
        return $this -> fetch('widget:page/breadcrumbs',['breadcrumbs'=>$breadcrumbs]);
    }

    public function title($breadcrumbs) {
        if(!empty($breadcrumbs)){
            $breadcrumb = array_pop($breadcrumbs);
            $title = $breadcrumb['name'];
        }else{
            $title = '首页';
        }
        return $this -> fetch('widget:page/title',['title'=>$title]);
    }

    public function search() {
        return $this -> fetch('widget:page/search');
    }
}
?>