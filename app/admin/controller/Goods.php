<?php

namespace app\admin\Controller;

use think\Request;

class Goods extends Admin
{

    public $goodsModel;

    public function __construct(){
        parent::__construct();
        $this->goodsModel = new \app\admin\Model\Goods();
    }

    //商品列表
    public function index(){
        $goods = $this->goodsModel->getGoods();
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
        if(IS_AJAX){
            $action = I('get.action');
            if($action == 'price'){ //更新价格
                $result = $this->updatePrice();
            }elseif($action == 'sku') {//更新库存
                $result = $this->updateSku();
            }else{
                $result = $this->goodsModel->save(I('post.'));
                if($result){
                    $result = array('code'=>1,'msg'=>'更新成功');
                }else{
                    $result = array('code'=>0,'msg'=>'更新失败');
                }
            }
        }else{
            $result = array('code'=>0,'msg'=>'异常提交');
        }
        $this->ajaxReturn($result);
    }

    //更新库存
    private function updateSku(){
        $goods_id = I('get.goods_id/d');
        $_product_id = I('get._product_id');
        $_store_nums = I('get._store_nums');
        $goods = array(
            'id' => $goods_id,
            'store_nums' => array_sum($_store_nums),
            'update_time' => time()
        );
        $result = $this->goodsModel->save($goods);
        if($result){
            foreach($_product_id as $key=>$product_id){
                $product = array(
                    'id' => $product_id,
                    'store_nums' => $_store_nums[$key]
                );

                M('Products')->save($product);
            }
            $result = array('code'=>1,'msg'=>'更新成功');
        }else{
            $result = array('code'=>0,'msg'=>'更新失败');
        }
        return $result;
    }

    //更新价格
    private function updatePrice(){
        $goods_id = I('get.goods_id/d');
        $_default = I('get._default/d');
        $_product_id = I('get._product_id');
        $_market_price = I('get._market_price');
        $_sell_price = I('get._sell_price');
        $_cost_price = I('get._cost_price');
        $goods = array(
            'id' => $goods_id,
            'market_price' => $_market_price[$_default],
            'sell_price' => $_sell_price[$_default],
            'cost_price' => $_cost_price[$_default],
            'update_time' => time()
        );
        $result = $this->goodsModel->save($goods);
        if($result){
            foreach($_product_id as $key=>$product_id){
                $product = array(
                    'id' => $product_id,
                    'market_price' => $_market_price[$key],
                    'sell_price' => $_sell_price[$key],
                    'cost_price' => $_cost_price[$key],
                );

                M('Products')->save($product);
            }
            $result = array('code'=>1,'msg'=>'更新成功');
        }else{
            $result = array('code'=>0,'msg'=>'更新失败');
        }
        return $result;
    }

    public function del(){
        $id = I('request.id/d');
        $result = $this->goodsModel->delGoodsById($id);
        if($result){
            $result = array('code'=>1,'msg'=>'删除成功');
        }else{
            $result = array('code'=>0,'msg'=>'删除失败');
        }
        $this->ajaxReturn($result);
    }

    public function __call($function,$args)
    {
        if ($function === 'spec') {
            $tpl = I('request.tpl');
            if ($tpl == 'select') {
                $has_id = I('request.has_id');
                $where = array();
                if (!empty($has_id)) {
                    $where['id'] = array('not in', implode(',', $has_id));
                }
                $specs = D('Spec')->get_specs('id,name', $where);
                $this->assign('specs', $specs);
            } elseif ($tpl == 'edit') {
                $id = I('request.id/d');
                $spec = D('Spec')->get_spec_by_id($id);
                $this->assign('spec', $spec);
            }
            $this->display(CONTROLLER_NAME . DS . ucfirst($function) . DS . $tpl);
        }elseif($function === 'product'){
            $tpl = I('request.tpl');
            if($tpl == 'price' || $tpl == 'sku' ){
                $id = I('request.id/d');
                $goods = $this->goodsModel->getGoodsById($id);
                $this->assign('goods', $goods);
                $products = $this->goodsModel->getProductsById($id);
                $this->assign('products', $products);
            }
            $this->display(CONTROLLER_NAME . DS . ucfirst($function) . DS . $tpl);
        }else{
            echo"你所调用的函数: ".$function."(参数: ";
            dump(array_merge(I('post.'),I('get.')));
            echo")<br>不存在！<br>";
        }
    }
}