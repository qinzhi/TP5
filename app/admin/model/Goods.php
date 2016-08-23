<?php

namespace app\admin\Model;

use think\Db;
use think\Request;
use traits\model\SoftDelete;


class Goods extends Common{

    use SoftDelete;

    /**
     * 删除时间
     * @var string
     */
    protected static $deleteTime = 'delete_time';

    protected $insert = ['create_time','update_time'];

    protected $update = ['update_time'];

    /**
     * 商品分类表
     */
    const TABLE_CATEGORY = 'goods_to_category';

    /**
     * 商品类型表
     */
    const TABLE_COMMEND = 'goods_to_commend';

    /**
     * 商品属性表
     */
    const TABLE_ATTR = 'goods_to_attr';

    /**
     * 商品详情表
     */
    const TABLE_DETAIL = 'goods_to_detail';

    /**
     * 商品SEO表
     */
    const TABLE_SEO = 'goods_to_seo';

    /**
     * 产品表
     */
    const TABLE_PRODUCT = 'product';

    public function setCreateTimeAttr(){
        return time();
    }

    public function setUpdateTimeAttr(){
        return time();
    }

    /**
     * 添加单个商品
     * @param $params
     */
    public function addGoods($params){

        $_default = isset($params['_default']) ? (int)$params['_default'] : 0;

        $_goods_no = $params['_goods_no'];
        $_store_nums = $params['_store_nums'];
        $_market_price = $params['_market_price'];
        $_sell_price = $params['_sell_price'];
        $_cost_price = $params['_cost_price'];
        $_weight = $params['_weight'];

        //计算总库存
        $store_total_nums = 0;
        foreach($_store_nums as $val){
            $store_total_nums += $val;
        }

        $goods = [
            'name' => $params['name'],
            'intro' => $params['intro'],
            'search_words' => $params['search_words'],
            'status' => intval($params['status']),
            'goods_no' => $_goods_no[$_default],
            'store_nums' => $store_total_nums,
            'market_price' => $_market_price[$_default],
            'sell_price' => $_sell_price[$_default],
            'cost_price' => $_cost_price[$_default],
            'weight' => $_weight[$_default],
        ];

        $goods_id = $this->data($goods)->save($goods);//添加商品

        if($goods_id > 0){

            /** --------   添加商品详情   --------- **/
            $detail = array(
                'goods_id' => $goods_id,
                'detail' => I('post.detail','','')
            );
            Db::table(self::TABLE_DETAIL)->insert($detail);

            /** --------   添加商品SEO   --------- **/
            $seo = [
                'goods_id' => $goods_id,
                'keywords' => $params['keywords'],
                'description' => $params['description']
            ];
            Db::table(self::TABLE_SEO)->save($seo);

            /** --------   添加商品类型   --------- **/
            $commend_type = $params['commend_type'];
            if(!empty($commend_type)){
                $commend = [];
                foreach($commend_type as $val){
                    $commend[] = [
                        'commend_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                Db::table(self::TABLE_COMMEND)->saveAll($commend);
            }

            /** --------   添加商品分类   --------- **/
            $category_id = $params['category_id'];
            if(!empty($category_id)){
                $category_id = explode(',',$category_id);
                $category = [];
                foreach($category_id as $val){
                    $category[] = [
                        'category_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                Db::table(self::TABLE_CATEGORY)->saveAll($category);
            }

            /** --------   添加商品扩展属性   --------- **/
            $model_id = $params['model_id'];
            $_attr = $params['_attr'];
            if($model_id > 0 && !empty($_attr)){
                $attr = [];
                foreach($_attr as $key => $val){
                    $attr[] = [
                        'goods_id' => $goods_id,
                        'model_id' => $model_id,
                        'attr_id' => $key,
                        'attr_value' => is_array($val) ? implode(',',$val) : $val
                    ];
                }
                Db::table(self::TABLE_ATTR)->saveAll($attr);
            }

            /** --------   添加規格商品   --------- **/
            $_spec_list = Request::instance()->post('_spec_list');
            $product = [];
            foreach($_goods_no as $key => $value){
                $product[] = [
                    'goods_id' => $goods_id,
                    'products_no' => $_goods_no[$key],
                    'spec_array' => !empty($_spec_list[$key]) ? "[".join(',',$_spec_list[$key])."]" : '',
                    'store_nums' => $_store_nums[$key],
                    'market_price' => $_market_price[$key],
                    'sell_price' => $_sell_price[$key],
                    'cost_price' => $_cost_price[$key],
                    'weight' => $_weight[$key],
                    'is_default' => ($_default == $key)?:0
                ];
            }
            Db::table(self::TABLE_PRODUCT)->saveAll($product);
        }
    }

    public function editGoodsById($params,$goods_id){
        $goods = array(
            'id' => $goods_id,
            'name' => $params['name'],
            'intro' => $params['intro'],
            'search_words' => $params['search_words'],
            'status' => (int)$params['status'],
        );

        $_default = isset($params['_default']) ? (int)$params['_default'] : 0;

        $_goods_no = $params['_goods_no'];
        $_store_nums = $params['_store_nums'];
        $_market_price = $params['_market_price'];
        $_sell_price = $params['_sell_price'];
        $_cost_price = $params['_cost_price'];
        $_weight = $params['_weight'];

        //计算总库存
        $store_total_nums = 0;
        foreach($_store_nums as $val){
            $store_total_nums += $val;
        }

        $goods['goods_no'] = $_goods_no[$_default];
        $goods['store_nums'] = $store_total_nums;
        $goods['market_price'] = $_market_price[$_default];
        $goods['sell_price'] = $_sell_price[$_default];
        $goods['cost_price'] = $_cost_price[$_default];
        $goods['weight'] = $_weight[$_default];

        if($this->save($goods)){//更新商品

            $map['goods_id'] = $goods_id;

            /** --------   更新商品详情   --------- **/
            $detail = array(
                'detail' => Request::instance()->post('trim','','')
            );
            Db::table(self::TABLE_DETAIL)->where($map)->save($detail);

            /** --------   更新商品SEO   --------- **/
            $seo = array(
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            Db::table(self::TABLE_SEO)->where($map)->save($seo);

            /** --------   添加商品类型   --------- **/
            $commend_type = $params['commend_type'];
            Db::table(self::TABLE_COMMEND)->where($map)->delete();//删除商品类型
            if(!empty($commend_type)){
                $commend = [];
                foreach($commend_type as $val){
                    $commend[] = [
                        'commend_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                Db::table(self::TABLE_COMMEND)->saveAll($commend);
            }

            /** --------   添加商品分类   --------- **/
            $category_id = $params['category_id'];
            Db::table(self::TABLE_CATEGORY)->where($map)->delete();//删除商品分类
            if(!empty($category_id)){
                $category_id = explode(',',$category_id);
                $category = [];
                foreach($category_id as $val){
                    $category[] = [
                        'category_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                Db::table(self::TABLE_CATEGORY)->saveAll($category);
            }

            /** --------   添加商品扩展属性   --------- **/
            $model_id = $params['model_id'];
            $_attr = $params['_attr'];
            Db::table(self::TABLE_ATTR)->where($map)->delete();//删除商品分类
            if($model_id > 0 && !empty($_attr)){
                $attr = [];
                foreach($_attr as $key => $val){
                    $attr[] = [
                        'goods_id' => $goods_id,
                        'model_id' => $model_id,
                        'attr_id' => $key,
                        'attr_value' => is_array($val) ? implode(',',$val) : $val
                    ];
                }
                Db::table(self::TABLE_ATTR)->saveAll($attr);
            }

            /** --------   更新規格商品   --------- **/
            $_spec_list = Request::instance()->post('_spec_list','','');
            $_product_id = $params['_product_id'];
            $delProduct = $params['delProduct'];
            if(!empty($delProduct)){
                Db::table(self::TABLE_PRODUCT)->where(array('id'=>array('in',$delProduct)))->save(array('is_del'=>1));
            }
            foreach($_goods_no as $key => $value){
                $product = array(
                    'goods_id' => $goods_id,
                    'products_no' => $_goods_no[$key],
                    'spec_array' => !empty($_spec_list[$key]) ? "[".join(',',$_spec_list[$key])."]" : '',
                    'store_nums' => $_store_nums[$key],
                    'market_price' => $_market_price[$key],
                    'sell_price' => $_sell_price[$key],
                    'cost_price' => $_cost_price[$key],
                    'weight' => $_weight[$key],
                    'is_default' => ($_default == $key)?:0
                );
                if(!empty($_product_id[$key])){
                    $product['id'] = $_product_id[$key];
                }
                Db::table(self::TABLE_PRODUCT)->save($product);
            }
        }
    }

    /**
     * 获取单个商品
     * @param $id
     * @return mixed
     */
    public function getGoodsById($id){
        return $this->alias('t')
                        ->join(self::TABLE_DETAIL . ' t1','t1.goods_id=t.id','LEFT')
                            ->join(self::TABLE_SEO . ' t2','t2.goods_id=t.id','LEFT')
                                ->where('t.id='.$id)->find();
    }

    /**
     * 获取单个商品分类
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsCategoriesById($goods_id){
        return Db::table(self::TABLE_CATEGORY)->where('goods_id',$goods_id)->select();
    }

    /**
     * 获取单个商品推荐类型
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsCommendById($goods_id){
        return Db::table(self::TABLE_COMMEND)->where('goods_id',$goods_id)->select();
    }

    /**
     * 获取单个商品属性
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsAttrById($goods_id){
        $attr = Db::table(self::TABLE_ATTR)->where('goods_id',$goods_id)->select();
        $arr = array();
        foreach($attr as $value){
            $arr[$value['model_id']][$value['attr_id']] = array(
                'model_id' => $value['model_id'],
                'attr_id' => $value['attr_id'],
                'attr_value' => $value['attr_value'],
                'sort' => $value['sort'],
            );
        }
        return $arr;
    }

    /**
     * 获取产品
     * @param $goods_id
     * @return mixed
     */
    public function getProductsById($goods_id){
        $map = array(
            'goods_id' => $goods_id,
            'is_del' => 0
        );
        return M('Products')->where($map)->select();
    }
}