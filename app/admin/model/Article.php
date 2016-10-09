<?php

namespace app\admin\model;

use app\common\Model\ArticleToDetail;
use app\common\Model\ArticleToSeo;
use think\Db;
use traits\model\SoftDelete;

class Article extends Common{

    use SoftDelete;

    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'delete_time';

    protected $insert = ['create_time','update_time'];

    protected $update = ['update_time'];

    public function setCreateTimeAttr(){
        return time();
    }

    public function setUpdateTimeAttr(){
        return time();
    }

    public function getArticles(){
        return $this->field('t.id,t.title,t.category_id,t.category_id,t.create_time,t.sort,t1.name as category')
                        ->alias('t')
                            ->join(ArticleCategory::TABLE_NAME . ' t1','t.category_id=t1.id','left')
                                ->select();
    }

    /**
     * 添加文章
     * @param $params
     */
    public function addArticle($params){
        $article = array(
            'title' => $params['title'],
            'subtitle' => $params['subtitle'],
            'category_id' => (int)$params['category_id'],
            'sort' => (int)$params['sort']
        );

        $status = $this->save($article);//添加文章
        if($status){
            $article_id = $this->getData('id');
            /** --------   添加文章详情   --------- **/
            $detail = array(
                'article_id' => $article_id,
                'detail' => $params['detail']
            );
            (new ArticleToDetail())->save($detail);

            /** --------   添加商品SEO   --------- **/
            $seo = array(
                'article_id' => $article_id,
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            (new ArticleToSeo())->save($seo);
        }
    }

    /**
     * 更新文章
     * @param $params
     * @param $article_id
     */
    public function editArticleById($params,$article_id){
        $article = array(
            'id' => $article_id,
            'title' => $params['title'],
            'subtitle' => $params['subtitle'],
            'category_id' => (int)$params['category_id'],
            'sort' => (int)$params['sort'],
            'update_time' => time()
        );

        if($this->update($article)){//更新商品

            $map['article_id'] = $article_id;

            /** --------   更新商品详情   --------- **/
            $detail = array(
                'detail' => $params['detail']
            );
            ArticleToDetail::where($map)->update($detail);

            /** --------   更新商品SEO   --------- **/
            $seo = array(
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            ArticleToSeo::where($map)->update($seo);
        }
    }

    /**
     * 获取单篇文章
     * @param $id
     * @return mixed
     */
    public function getArticleById($id){
        return $this->field('t.*,t1.detail,t2.keywords,t2.description,t3.name as category')
                        ->alias('t')
                            ->join(ArticleToDetail::TABLE_NAME . ' t1','t1.article_id = t.id','left')
                                ->join(ArticleToSeo::TABLE_NAME . ' t2','t2.article_id = t.id','left')
                                    ->join(ArticleCategory::TABLE_NAME . ' t3','t3.id = t.category_id','left')
                                        ->where('t.id',$id)->find();
    }
}