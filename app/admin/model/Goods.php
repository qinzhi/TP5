<?php

namespace app\admin\Model;

use app\common\Model\GoodsToAttr;
use app\common\Model\GoodsToCategory;
use app\common\Model\GoodsToCommend;
use app\common\Model\GoodsToDetail;
use app\common\Model\GoodsToSeo;
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
     * 产品表
     */
    const TABLE_PRODUCT = 'products';

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
        $store_total_num = 0;
        foreach($_store_nums as $val){
            $store_total_num += $val;
        }

        $goods = [
            'name' => $params['name'],
            'intro' => $params['intro'],
            'search_words' => $params['search_words'],
            'status' => intval($params['status']),
            'goods_no' => $_goods_no[$_default],
            'store_nums' => $store_total_num,
            'market_price' => $_market_price[$_default],
            'sell_price' => $_sell_price[$_default],
            'cost_price' => $_cost_price[$_default],
            'weight' => $_weight[$_default],
        ];

        $status = $this->data($goods)->save();//添加商品

        if($status){
            $goods_id = $this->getData('id');
            /** --------   添加商品详情   --------- **/
            $detail = array(
                'goods_id' => $goods_id,
                'detail' => Request::instance()->post('detail','','trim')
            );
            (new GoodsToDetail())->save($detail);

            /** --------   添加商品SEO   --------- **/
            $seo = [
                'goods_id' => $goods_id,
                'keywords' => $params['keywords'],
                'description' => $params['description']
            ];
            (new GoodsToSeo())->save($seo);

            /** --------   添加商品类型   --------- **/
            if(!empty($params['commend_type'])){
                $commend_type = $params['commend_type'];
                $commend = [];
                foreach($commend_type as $val){
                    $commend[] = [
                        'commend_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                (new GoodsToCommend())->saveAll($commend);
            }

            /** --------   添加商品分类   --------- **/
            if(!empty($params['category_id'])){
                $category_id = explode(',',$params['category_id']);
                $category = [];
                foreach($category_id as $val){
                    $category[] = [
                        'category_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                (new GoodsToCategory())->saveAll($category);
            }

            /** --------   添加商品扩展属性   --------- **/
            if(!empty($params['model_id']) && !empty($params['_attr'])){
                $model_id = $params['model_id'];
                $_attr = $params['_attr'];
                $attr = [];
                foreach($_attr as $key => $val){
                    $attr[] = [
                        'goods_id' => $goods_id,
                        'model_id' => $model_id,
                        'attr_id' => $key,
                        'attr_value' => is_array($val) ? implode(',',$val) : $val
                    ];
                }
                (new GoodsToAttr())->saveAll($attr);
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
            (new Products())->saveAll($product);
        }
    }

    public function editGoodsById($params,$goods_id){

        $_default = isset($params['_default']) ? (int)$params['_default'] : 0;

        $_goods_no = $params['_goods_no'];
        $_store_nums = $params['_store_nums'];
        $_market_price = $params['_market_price'];
        $_sell_price = $params['_sell_price'];
        $_cost_price = $params['_cost_price'];
        $_weight = $params['_weight'];

        //计算总库存
        $store_total_num = 0;
        foreach($_store_nums as $val){
            $store_total_num += $val;
        }

        $goods = [
            'name' => $params['name'],
            'intro' => $params['intro'],
            'search_words' => $params['search_words'],
            'status' => intval($params['status']),
            'goods_no' => $_goods_no[$_default],
            'store_nums' => $store_total_num,
            'market_price' => $_market_price[$_default],
            'sell_price' => $_sell_price[$_default],
            'cost_price' => $_cost_price[$_default],
            'weight' => $_weight[$_default],
        ];

        if($this->save($goods,['id' => $goods_id])){//更新商品

            $map['goods_id'] = $goods_id;

            /** --------   更新商品详情   --------- **/
            $detail = array(
                'detail' => Request::instance()->post('detail','','trim')
            );
            GoodsToDetail::where($map)->update($detail);

            /** --------   更新商品SEO   --------- **/
            $seo = array(
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            GoodsToSeo::where($map)->update($seo);

            /** --------   添加商品类型   --------- **/
            $commend_type = $params['commend_type'];
            $goodsToCommendModel = new GoodsToCommend();
            $goodsToCommendModel->where($map)->delete();//删除商品类型
            if(!empty($commend_type)){
                $commend = [];
                foreach($commend_type as $val){
                    $commend[] = [
                        'commend_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                $goodsToCommendModel->saveAll($commend);
            }

            /** --------   添加商品分类   --------- **/
            $category_id = $params['category_id'];
            $goodsToCategoryModel = new GoodsToCategory();
            $goodsToCategoryModel->where($map)->delete();//删除商品分类
            if(!empty($category_id)){
                $category_id = explode(',',$category_id);
                $category = [];
                foreach($category_id as $val){
                    $category[] = [
                        'category_id' => $val,
                        'goods_id' => $goods_id
                    ];
                }
                $goodsToCategoryModel->saveAll($category);
            }

            /** --------   添加商品扩展属性   --------- **/
            $model_id = $params['model_id'];
            $_attr = $params['_attr'];
            $goodsToAttrModel = new GoodsToAttr();
            $goodsToAttrModel->where($map)->delete();//删除商品分类
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
                $goodsToAttrModel->saveAll($attr);
            }

            /** --------   更新規格商品   --------- **/
            $productModel = new Products();
            $_spec_list = $params['_spec_list'];
            $_product_id = $params['_product_id'];

            if(!empty($params['delProduct'])){
                $productModel->delete($params['delProduct']);//删除商品
            }

            $product = [];
            foreach($_goods_no as $key => $value){
                $product[$key] = [
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
                if(!empty($_product_id[$key])){
                    $product[$key]['id'] = $_product_id[$key];
                }
            }
            $productModel->saveAll($product);
        }
    }

    /**
     * 获取单个商品
     * @param $id
     * @return mixed
     */
    public function getGoodsById($id){
        return $this->alias('t')
                        ->join(GoodsToDetail::TABLE_NAME . ' t1','t1.goods_id=t.id','LEFT')
                            ->join(GoodsToSeo::TABLE_NAME . ' t2','t2.goods_id=t.id','LEFT')
                                ->where('t.id',$id)->find();
    }

    /**
     * 获取单个商品分类
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsCategoriesById($goods_id){
        return GoodsToCategory::where('goods_id',$goods_id)->select();
    }

    /**
     * 获取单个商品推荐类型
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsCommendById($goods_id){
        return GoodsToCommend::where('goods_id',$goods_id)->select();
    }

    /**
     * 获取单个商品属性
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsAttrById($goods_id){
        $attr = GoodsToAttr::where('goods_id',$goods_id)->select();
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
        return Products::where('goods_id',$goods_id)->select();
    }
}