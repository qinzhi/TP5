<?php
namespace app\weixin\controller;

use app\admin\model\GoodsCategory;

class Category extends Weixin
{
    public function __construct()
    {
        parent::__construct();
        $this->assign('nav_type',2);
    }

    public function index(){
        $categoryModel = new GoodsCategory();
        $categories = $categoryModel->getCategories();
        foreach ($categories as &$category){
            $category['icon'] = get_img_url($category['icon']);
            $category['url'] = url('goods/lists',['id' => $category['id']]);
        }
        $this->assign('categories',json_encode($categories,JSON_UNESCAPED_UNICODE));
        $this->assign('cartNum',$this->getCartNum());
        return $this->fetch();
    }
}
