<?php

namespace app\admin\Controller;
use think\Loader;
use think\Request;

/**
 * 文章管理控制器
 * Class AuthController
 * Author Qinzhi
 */
class Article extends Admin {

    public $articleModel;

    public function __construct(){
        parent::__construct();
        $this->articleModel = Loader::model('article');
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
            $this->display();
        }
    }

}