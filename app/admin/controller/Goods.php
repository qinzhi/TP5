<?php

namespace app\admin\Controller;

use app\admin\Model\Products;
use think\Db;
use think\Loader;
use think\Request;
use app\admin\Model\Goods as GoodsModel;

class Goods extends Admin
{

    public $goodsModel;

    public function __construct(){
        parent::__construct();
        $this->goodsModel = new GoodsModel();
    }

    //商品列表
    public function index(){
        $goods = $this->goodsModel->paginate();
        $this->assign('goods',$goods);
        $this->assign('categories_id',0);
        return $this->fetch();
    }

    //添加商品
    public function add(){
        if(Request::instance()->isPost()){
            $this->goodsModel->addGoods(Request::instance()->post());
            $this->redirect('goods/index');
        }else{
            $unit = Db::name('unit')->where('is_close',0)->select();//单位名称
            $this->assign('unit',$unit);
            return $this->fetch();
        }
    }

    //编辑商品
    public function edit($id){
        if(Request::instance()->isPost()){
            $this->goodsModel->editGoodsById(Request::instance()->post(),$id);
            $this->redirect('goods/index');
        }else{
            $unit = Db::name('unit')->where('is_close',0)->select();//单位名称
            $this->assign('unit',$unit);
            
            $goods = $this->goodsModel->getGoodsById($id);
            $this->assign('goods',$goods);

            //商品图片
            $images = $this->goodsModel->getGoodsImageById($id);
            foreach ($images as &$val){
                $val['imageUrl'] = get_img($val['image']);
            }
            $this->assign('images',$images);
            
            //商品分类
            $categories = $this->goodsModel->getGoodsCategoriesById($id);
            $categories_id = array();
            foreach($categories as $value){
                array_push($categories_id,$value['category_id']);
            }
            $this->assign('categories_id',json_encode($categories_id));

            //商品推荐类型
            $commend = $this->goodsModel->getGoodsCommendById($id);
            $commend_id = array();
            foreach($commend as $value){
                array_push($commend_id,$value['commend_id']);
            }
            $this->assign('commend_id',$commend_id);

            //商品属性
            $attr = $this->goodsModel->getGoodsAttrById($id);
            $this->assign('model_id',key($attr));//商品模型id
            $this->assign('attr',current($attr));

            //产品
            $products = $this->goodsModel->getProductsById($id);
            $cur = current($products);
            $this->assign('no_spec',empty($cur['spec_array'])?:false);
            $this->assign('products',json_encode($products));
            return $this->fetch();
        }
    }

    //更新商品
    public function update(){
        if(Request::instance()->isAjax()){
            $productModel = new Products();
            $action = Request::instance()->post('action');
            if($action == 'price'){ //更新价格
                $result = $this->updatePrice($productModel,$_POST);
            }elseif($action == 'sku') {//更新库存
                $result = $this->updateSku($productModel,$_POST);
            }else{
                if(input('?post.status')){
                    if(input('post.status') == 0){
                        $_POST['down_time'] = time();
                    }else{
                        $_POST['up_time'] = time();
                    }
                }
                $result = $this->goodsModel->update($_POST);
                if($result){
                    return ['code'=>1,'msg'=>'更新成功'];
                }else{
                    return ['code'=>0,'msg'=>'更新失败'];
                }
            }
        }else{
            return ['code'=>0,'msg'=>'异常提交'];
        }
        return $result;
    }

    //更新库存
    private function updateSku(Products $productModel,Array $post){
        $goods_id = $post['goods_id'];
        $_product_id = $post['_product_id'];
        $_store_nums = $post['_store_nums'];
        $goods = [
            'id' => $goods_id,
            'store_nums' => array_sum($_store_nums),
            'update_time' => time()
        ];
        $result = $this->goodsModel->update($goods);
        if($result){
            $product = [];
            foreach($_product_id as $key=>$product_id){
                $product[] = [
                    'id' => $product_id,
                    'store_nums' => $_store_nums[$key]
                ];
            }
            $productModel->saveAll($product);
            $result = ['code'=>1,'msg'=>'更新成功'];
        }else{
            $result = ['code'=>0,'msg'=>'更新失败'];
        }
        return $result;
    }

    //更新价格
    private function updatePrice(Products $productModel,Array $post){
        $goods_id = $post['goods_id'];
        $_default = $post['_default'];
        $_product_id = $post['_product_id'];
        $_market_price = $post['_market_price'];
        $_sell_price = $post['_sell_price'];
        $_cost_price = $post['_cost_price'];
        $goods = array(
            'id' => $goods_id,
            'market_price' => $_market_price[$_default],
            'sell_price' => $_sell_price[$_default],
            'cost_price' => $_cost_price[$_default],
            'update_time' => time()
        );
        $result = $this->goodsModel->update($goods);
        if($result){
            $product = [];
            foreach($_product_id as $key=>$product_id){
                $product[] = [
                    'id' => $product_id,
                    'market_price' => $_market_price[$key],
                    'sell_price' => $_sell_price[$key],
                    'cost_price' => $_cost_price[$key],
                ];
            }
            $productModel->saveAll($product);
            $result = ['code'=>1,'msg'=>'更新成功'];
        }else{
            $result = ['code'=>0,'msg'=>'更新失败'];
        }
        return $result;
    }

    public function del($id){
        $result = GoodsModel::destroy($id);
        if($result){
            $result = ['code'=>1,'msg'=>'删除成功'];
        }else{
            $result = ['code'=>0,'msg'=>'删除失败'];
        }
        return $result;
    }

    public function _empty(){
        $action = Request::instance()->action();
        $tpl = Request::instance()->request('tpl');
        if ($action === 'spec') {
            if ($tpl == 'select') {
                $has_id = Request::instance()->request('has_id');
                $where = array();
                if (!empty($has_id)) {
                    $where['id'] = array('not in', implode(',', $has_id));
                }
                $specs = Loader::model('spec')->where($where)->get();
                $this->assign('specs', $specs);
            } elseif ($tpl == 'edit') {
                $id = Request::instance()->request('id');
                $spec = Loader::model('spec')->get($id);
                $this->assign('spec', $spec);
            }
            return $this->fetch("goods/$action/$tpl");
        }elseif($action === 'product'){
            if($tpl == 'price' || $tpl == 'sku' ){
                $id = Request::instance()->request('id');
                $goods = $this->goodsModel->getGoodsById($id);
                $this->assign('goods', $goods);
                $products = $this->goodsModel->getProductsById($id);
                $this->assign('products', $products);
            }
            return $this->fetch("goods/$action/$tpl");
        }else{
            echo "你所调用的函数: ".$action."(参数: ";
            dump(Request::instance()->request());
            echo ")<br>不存在！<br>";
        }
    }
}