<?php

namespace app\admin\Model;

class Goods extends Common{

    public function __construct(){
        parent::__construct();
    }

    public function getGoods(){
        $map['is_del'] = 0;
        return $this->where($map)->select();
    }

    /**
     * 添加单个商品
     * @param $params
     */
    public function addGoods($params){
        $goods = array(
            'name' => $params['name'],
            'intro' => $params['intro'],
            'search_words' => $params['search_words'],
            'status' => (int)$params['status'],
            'create_time' => time(),
            'update_time' => time()
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

        $goods_id = $this->add($goods);//添加商品
        if($goods_id > 0){

            /** --------   添加商品详情   --------- **/
            $detail = array(
                'goods_id' => $goods_id,
                'detail' => I('post.detail','','')
            );
            M('GoodsToDetail')->add($detail);

            /** --------   添加商品SEO   --------- **/
            $seo = array(
                'goods_id' => $goods_id,
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            M('GoodsToSeo')->add($seo);

            /** --------   添加商品类型   --------- **/
            $commend_type = $params['commend_type'];
            if(!empty($commend_type)){
                foreach($commend_type as $val){
                    $commend[] = array(
                        'commend_id' => $val,
                        'goods_id' => $goods_id
                    );
                }
                M('GoodsToCommend')->addAll($commend);
            }

            /** --------   添加商品分类   --------- **/
            $category_id = $params['category_id'];
            if(!empty($category_id)){
                $category_id = explode(',',$category_id);
                foreach($category_id as $val){
                    $category[] = array(
                        'category_id' => $val,
                        'goods_id' => $goods_id
                    );
                }
                M('GoodsToCategory')->addAll($category);
            }

            /** --------   添加商品扩展属性   --------- **/
            $model_id = $params['model_id'];
            $_attr = $params['_attr'];
            if($model_id > 0 && !empty($_attr)){
                foreach($_attr as $key => $val){
                    $attr = array(
                        'goods_id' => $goods_id,
                        'model_id' => $model_id,
                        'attr_id' => $key,
                        'attr_value' => is_array($val) ? implode(',',$val) : $val
                    );
                    M('GoodsToAttr')->add($attr);
                }
            }

            /** --------   添加規格商品   --------- **/
            $_spec_list = I('post._spec_list','','');
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
                M('Products')->add($product);
            }
        }
    }

    public function editGoodsById($params,$goods_id){
        $goods = array(
            'id' => $goods_id,
            'name' => $params['name'],
            'intro' => $params['intro'],
            'search_words' => $params['search_words'],
            'status' => (int)$params['status'],
            'update_time' => time()
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
                'detail' => I('post.detail','','')
            );
            M('GoodsToDetail')->where($map)->save($detail);

            /** --------   更新商品SEO   --------- **/
            $seo = array(
                'keywords' => $params['keywords'],
                'description' => $params['description']
            );
            M('GoodsToSeo')->where($map)->save($seo);

            /** --------   添加商品类型   --------- **/
            $commend_type = $params['commend_type'];
            M('GoodsToCommend')->where($map)->delete();//删除商品类型
            if(!empty($commend_type)){
                foreach($commend_type as $val){
                    $commend[] = array(
                        'commend_id' => $val,
                        'goods_id' => $goods_id
                    );
                }
                M('GoodsToCommend')->addAll($commend);
            }

            /** --------   添加商品分类   --------- **/
            $category_id = $params['category_id'];
            M('GoodsToCategory')->where($map)->delete();//删除商品分类
            if(!empty($category_id)){
                $category_id = explode(',',$category_id);
                foreach($category_id as $val){
                    $category[] = array(
                        'category_id' => $val,
                        'goods_id' => $goods_id
                    );
                }
                M('GoodsToCategory')->addAll($category);
            }

            /** --------   添加商品扩展属性   --------- **/
            $model_id = $params['model_id'];
            $_attr = $params['_attr'];
            M('GoodsToAttr')->where($map)->delete();//删除商品分类
            if($model_id > 0 && !empty($_attr)){
                foreach($_attr as $key => $val){
                    $attr = array(
                        'goods_id' => $goods_id,
                        'model_id' => $model_id,
                        'attr_id' => $key,
                        'attr_value' => is_array($val) ? implode(',',$val) : $val
                    );
                    M('GoodsToAttr')->add($attr);
                }
            }

            /** --------   更新規格商品   --------- **/
            $_spec_list = I('post._spec_list','','');
            $_product_id = $params['_product_id'];
            $delProduct = $params['delProduct'];
            if(!empty($delProduct)){
                M('Products')->where(array('id'=>array('in',$delProduct)))->save(array('is_del'=>1));
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
                    M('Products')->save($product);
                }else{
                    M('Products')->add($product);
                }
            }
        }
    }

    /**
     * 逻辑删除单个商品
     * @param $id
     * @return bool
     */
    public function delGoodsById($id){
        return $this->where(array('id'=>$id))->save(array('is_del'=>1));
    }

    /**
     * 获取单个商品
     * @param $id
     * @return mixed
     */
    public function getGoodsById($id){
        return $this->table($this->tablePrefix . 'goods as t')
                        ->join('left join ' . $this->tablePrefix . 'goods_to_detail as t1 on t1.goods_id = t.id')
                            ->join('left join ' . $this->tablePrefix . 'goods_to_seo as t2 on t2.goods_id = t.id')
                                ->where('t.id='.$id)->find();
    }

    /**
     * 获取单个商品分类
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsCategoriesById($goods_id){
        return M('GoodsToCategory')->where('goods_id='.$goods_id)->select();
    }

    /**
     * 获取单个商品推荐类型
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsCommendById($goods_id){
        return M('GoodsToCommend')->where('goods_id='.$goods_id)->select();
    }

    /**
     * 获取单个商品属性
     * @param $goods_id
     * @return mixed
     */
    public function getGoodsAttrById($goods_id){
        $attr = M('GoodsToAttr')->where('goods_id='.$goods_id)->select();
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