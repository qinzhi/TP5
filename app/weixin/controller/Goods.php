<?php
namespace app\weixin\controller;

use app\common\model\GoodsToAttr;
use app\common\model\GoodsToImages;
use app\common\model\Products;
use app\common\model\Goods as GoodsModel;
use think\Db;
use think\Request;

class Goods extends Weixin
{

    public $limit = 10;
    
    public function lists(){
        $this->assign('limit',$this->limit);
        return $this->fetch();
    }

    public function getGoodsList($field = '',$sort='desc',$keyword = ''){
        if(!empty($field)){
            $params['field'] = $field;
            $params['sort'] = $sort;
        }
        $params['status'] = 1;//上架的商品
        $params['keyword'] = $keyword;
        $page = Request::instance()->request('page',1);
        $offset = ($page - 1) * $this->limit;
        $goodsModel = new GoodsModel();
        $goodsList = $goodsModel->getGoodsList($params,$offset,$this->limit);
        foreach ($goodsList as $key => &$val){
            $val['cover_image'] = get_img($val['cover_image']);
            $val['url'] = url('goods/detail',['id'=>$val['id']]);
        }
        $result['goodsNum'] = $goodsModel->getGoodsNum($params);
        $result['goodsList'] = $goodsList;
        return $result;
    }

    public function detail($id){
        $goodsModel = new GoodsModel();
        $goods = $goodsModel->getGoodsById($id);
        $this->assign('goods',$goods);

        $product = Products::where('goods_id',$id)->find();
        $this->assign('product',$product);

        $images = GoodsToImages::where('goods_id',$id)->select();
        $this->assign('images',$images);

        $goodsAttrModel = new GoodsToAttr();
        $attr = $goodsAttrModel->getGoodsAttr($id);
        $this->assign('attr',$attr);

        $favorite = Db::name('favorite')->where('member_id',$this->member['id'])->where('goods_id',$id)->find();
        $this->assign('is_favorite',!empty($favorite)?:0);

        $this->assign('cartNum',$this->getCartNum());
        return $this->fetch();
    }

    /**
     * 收藏商品
     */
    public function collect($goods_id){
        $goodsModel = new GoodsModel();
        $goods = $goodsModel->getGoodsById($goods_id);
        if(!empty($goods)){
            $favorite = Db::name('favorite')->where('member_id',$this->member['id'])->where('goods_id',$goods_id)->find();
            if(!empty($favorite)){
                $result = Db::name('favorite')->where('member_id',$this->member['id'])->where('goods_id',$goods_id)->delete();
                if($result){
                    GoodsModel::where('id',$goods_id)->setDec('favorite');
                    return ['code' => 1,'msg'=>'已取消收藏','label'=>'收藏','action' => 'cancel'];
                }else{
                    return ['code' => 0,'msg'=>'取消收藏失败','label'=>'收藏'];
                }
            }else{
                $result = Db::name('favorite')->insert([
                    'member_id' => $this->member['id'],
                    'goods_id' => $goods_id,
                    'add_time' => time(),
                ]);
                if($result){
                    GoodsModel::where('id',$goods_id)->setInc('favorite');
                    return ['code' => 1,'msg'=>'商品已收藏','label'=>'已收藏','action' => 'plus'];
                }else{
                    return ['code' => 0,'msg'=>'商品收藏失败','label'=>'已收藏'];
                }
            }
        }else{
            return ['code' => -1,'msg' => '商品不存在'];
        }
    }

    public function getProductList($goods_id){
        $products = Products::where('goods_id',$goods_id)->select();
        if(count($products) == 1){
            $arr = [
                'is_single' => true,
                'products' => $products
            ];
        }else if(count($products) > 1){
            $properties = [];
            foreach ($products as $key=>$product){
                $spec_arr = json_decode($product['spec_array'],true);
                foreach ($spec_arr as $k=>$spec){
                    if(!isset($properties[$spec['name']])) $properties[$spec['name']] = [];
                    if(in_array($spec['value'],$properties[$spec['name']]) === false)
                        $properties[$spec['name']][] = $spec['value'];
                }
            }
            $arr = [
                'is_single' => false,
                'properties' => $properties,
                'products' => $products
            ];
        }
        return json($arr);
    }
}