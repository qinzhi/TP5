<?php

namespace app\admin\Controller;

use app\admin\Model\Product;
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
            $this->goodsModel->addGoods(I('post.'));
            $this->redirect('goods/index');
        }else{
            return $this->fetch();
        }
    }

    //编辑商品
    public function edit(){
        $id = Request::instance()->get('id');
        if(Request::instance()->isPost()){
            $this->goodsModel->editGoodsById(I('post.'),$id);
            $this->redirect('Goods/index');
        }else{
            $goods = $this->goodsModel->getGoodsById($id);
            $this->assign('goods',$goods);

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
            $productModel = new Product();
            $action = Request::instance()->get('action');
            if($action == 'price'){ //更新价格
                $result = $this->updatePrice($productModel);
            }elseif($action == 'sku') {//更新库存
                $result = $this->updateSku($productModel);
            }else{
                $result = $this->goodsModel->save(I('post.'));
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
    private function updateSku(Product $productModel){
        $goods_id = Request::instance()->get('goods_id');
        $_product_id = Request::instance()->get('_product_id');
        $_store_nums = Request::instance()->get('_store_nums');
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
    private function updatePrice(Product $productModel){
        $goods_id = Request::instance()->get('goods_id');
        $_default = Request::instance()->get('_default');
        $_product_id = Request::instance()->get('_product_id');
        $_market_price = Request::instance()->get('_market_price');
        $_sell_price = Request::instance()->get('_sell_price');
        $_cost_price = Request::instance()->get('_cost_price');
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

    public function del(){
        $id = Request::instance()->get('id');
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
        }
    }

    public function __call($function,$args)
    {
        if ($function === 'spec') {
            $tpl = Request::instance()->request('tpl');
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
            return $this->fetch(Request::instance()->controller() . DS . ucfirst($function) . DS . $tpl);
        }elseif($function === 'product'){
            $tpl = Request::instance()->request('tpl');
            if($tpl == 'price' || $tpl == 'sku' ){
                $id = Request::instance()->request('id');
                $goods = $this->goodsModel->getGoodsById($id);
                $this->assign('goods', $goods);
                $products = $this->goodsModel->getProductsById($id);
                $this->assign('products', $products);
            }
            return $this->fetch(Request::instance()->controller() . DS . ucfirst($function) . DS . $tpl);
        }else{
            echo "你所调用的函数: ".$function."(参数: ";
            dump(Request::instance()->request());
            echo ")<br>不存在！<br>";
        }
    }
}