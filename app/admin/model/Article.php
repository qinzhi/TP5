<?php

namespace app\admin\Model;

use think\Db;
use traits\model\SoftDelete;

class Article extends Common{

    use SoftDelete;

    const TABLE_CATEGORY = 'article_category';

    const TABLE_DETAIL = 'article_to_detail';

    const TABLE_SEO = 'article_to_seo';

    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'del_time';

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
                            ->join(self::TABLE_CATEGORY . ' t1','t.category_id=t1.id')
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

        $article_id = $this->save($article);//添加文章
        if($article_id > 0){

            /** --------   添加文章详情   --------- **/
            $detail = array(
                'article_id' => $article_id,
                'detail' => $params['detail']
            );
            Db::table('ArticleToDetail')->save($detail);

            /** --------   添加商品SEO   --------- **/
            $seo = array(
                'article_id' => $article_id,
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            Db::table('ArticleToSeo')->save($seo);
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

        if($this->save($article)){//更新商品

            $map['article_id'] = $article_id;

            /** --------   更新商品详情   --------- **/
            $detail = array(
                'detail' => $params['detail']
            );
            Db::table('ArticleToDetail')->where($map)->save($detail);

            /** --------   更新商品SEO   --------- **/
            $seo = array(
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            Db::table('ArticleToSeo')->where($map)->save($seo);
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
                            ->join('left join ' . $this->tablePrefix . 'article_to_detail as t1 on t1.article_id = t.id')
                                ->join('left join ' . $this->tablePrefix . 'article_to_seo as t2 on t2.article_id = t.id')
                                    ->join('left join ' . $this->tablePrefix . 'article_category as t3 on t3.id = t.category_id')
                                        ->where('t.id='.$id)->find();
    }
}