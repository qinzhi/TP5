<?php

namespace app\admin\controller;

use app\common\tools\Tree;
use think\Request;
use app\admin\model\GoodsCategory as GoodsCategoryModel;

class GoodsCategory extends Admin {

    public $category;

    public function __construct(){
        parent::__construct();
        $this->category = new GoodsCategoryModel();
    }

    public function index(){
        $categories = $this->category->getCategories();
        $tree = new Tree($categories);
        $goodsCategories = $tree->leaf();
        $this->assign('goodsCategories',$goodsCategories);
        $this->assign('tree',$this->category->formatTree($categories));
        return $this->fetch('goods/category');
    }

    public function getCategory(){
        $id = Request::instance()->request('id');
        $category = $this->category->getCategoryById($id);

        return $category;
    }

    public function getCategoriesTree(){
        $categories = $this->category->getCategories();
        $tree = new Tree($categories);
        $categories = $tree->leaf();
        $tree = $this->category->formatTree($categories,false);
        return $tree;
    }

    public function add(){
        if(Request::instance()->isPost()){
            $pid = Request::instance()->request('p_id','','intval');
            $pCategory = $this->category->getCategoryByPid($pid);
            if($pid == 0) $level = 0;
            else{
                $category = $this->category->getCategoryById($pid);
                $level = $category['level'] + 1;
            }
            $sort = !empty($pCategory) ? ($pCategory['sort'] + 1) : 0;
            $data = [
                'pid' => $pid,
                'level' => $level,
                'name' => Request::instance()->request('name','','trim'),
                'sort' => $sort,
            ];
            $seo = [
                'title' => Request::instance()->request('title','','trim'),
                'keywords' => Request::instance()->request('keywords','','trim'),
                'descript' => Request::instance()->request('descript','','trim'),
            ];
            $result = $this->category->addCategory($data,$seo);
            if($result === false){
                $this->error('分类添加失败',url('goodsCategory/index'));
                return;
            }
        }
        $this->redirect('goodsCategory/index');
    }

    public function edit(){
        if(Request::instance()->isAjax()){
            parse_str(urldecode(Request::instance()->request('params')),$params);
            $pid = $params['p_id'];
            $category = $this->category->getCategoryById($pid);
            if($pid == 0) $level = 0;
            else $level = $category['level'] + 1;
            $id = $params['id'];
            $data = array(
                'pid' => $params['p_id'],
                'level' => $level,
                'name' => trim($params['name']),
            );
            $seo = array(
                'title' => trim($params['title']),
                'keywords' => trim($params['keywords']),
                'descript' => trim($params['descript']),
            );
            $result = $this->category->updateCategoryById($data,$seo,$id);
            if($result){
                $result = ['code'=>1,'msg'=>'保存成功'];
            }else{
                $result = ['code'=>0,'msg'=>'保存失败'];
            }
        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }

        return $result;
    }

    public function del(){
        if(Request::instance()->isAjax()){
            $id = Request::instance()->request('id');
            $category = $this->category->getCategoryByPid($id);
            if(!empty($category)){
                $result = ['code'=>0,'msg'=>'不能直接删除上级模块'];
            }else{
                if($this->category->destroy($id)){
                    $result = ['code'=>1,'msg'=>'删除成功'];
                }else{
                    $result = ['code'=>0,'msg'=>'删除失败'];
                }
            }
        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }
        return $result;
    }

    public function move(){
        if(Request::instance()->isAjax()){
            $id = Request::instance()->request('id');
            $action = Request::instance()->request('action');
            $result = $this->category->move($id,$action);
            if($result){
                $result = ['code'=>1,'msg'=>'移动成功'];
            }else{
                $result = ['code'=>0,'msg'=>'移动失败'];
            }

        }else{
            $result = ['code'=>0,'msg'=>'异常提交'];
        }
        return $result;
    }

}