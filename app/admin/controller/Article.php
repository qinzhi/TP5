<?php

namespace app\admin\controller;

use think\Request;
use app\admin\model\Article as ArticleModel;

/**
 * 文章管理控制器
 * Class AuthController
 * Author Qinzhi
 */
class Article extends Admin {

    public $articleModel;

    public function __construct(){
        parent::__construct();
        $this->articleModel = new ArticleModel();
    }

    public function index(){
        $articles = $this->articleModel->getArticles();
        $this->assign('articles',$articles);
        $this->assign('categories_id',0);
        return $this->fetch();
    }

    public function add(){
        if(Request::instance()->isPost()){
            $this->articleModel->addArticle(Request::instance()->post());
            $this->redirect('article/index');
        }else{
            return $this->fetch();
        }
    }

    public function edit($id){
        if(Request::instance()->isPost()){
            $this->articleModel->editArticleById(Request::instance()->post(),$id);
            $this->redirect('article/index');
        }else{
            $article = $this->articleModel->getArticleById($id);
            $this->assign('article',$article);
            return $this->fetch();
        }
    }

    public function del($id){
        $result = ArticleModel::destroy($id);
        if($result){
            $result = ['code'=>1,'msg'=>'删除成功'];
        }else{
            $result = ['code'=>0,'msg'=>'删除失败'];
        }
        return $result;
    }

}